<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SincronizacaoTest extends TestCase
{
    use RefreshDatabase;

    // roda antes de cada teste — popula as tabelas base
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\TabelasBaseSeeder::class);
    }

    // ==================== PRODUTOS ====================

    /** @test */
    public function sincronizar_produtos_retorna_200()
    {
        $response = $this->postJson('/api/sincronizar/produtos');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'inseridos',
                     'atualizados',
                     'removidos',
                 ]);
    }

    /** @test */
    public function sincronizar_produtos_insere_registros_na_tabela_destino()
    {
        $this->postJson('/api/sincronizar/produtos');

        // produtos ativos na base são 10 (PRD005 e PRD012 são inativos)
        $this->assertDatabaseCount('produto_insercao', 10);
    }

    /** @test */
    public function sincronizar_produtos_nao_insere_produtos_inativos()
    {
        $this->postJson('/api/sincronizar/produtos');

        $this->assertDatabaseMissing('produto_insercao', [
            'prod_cod' => 'PRD005',
        ]);

        $this->assertDatabaseMissing('produto_insercao', [
            'prod_cod' => 'PRD012',
        ]);
    }

    /** @test */
    public function sincronizar_produtos_normaliza_dados()
    {
        $this->postJson('/api/sincronizar/produtos');

        // PRD001 vinha com espaço e minúsculo na base
        $this->assertDatabaseHas('produto_insercao', [
            'prod_cod' => 'PRD001',
        ]);

        // prd002 vinha com espaço e minúsculo
        $this->assertDatabaseHas('produto_insercao', [
            'prod_cod' => 'PRD002',
        ]);
    }

    /** @test */
    public function sincronizar_produtos_evita_duplicidade()
    {
        // roda duas vezes
        $this->postJson('/api/sincronizar/produtos');
        $this->postJson('/api/sincronizar/produtos');

        // deve continuar com 10, sem duplicar
        $this->assertDatabaseCount('produto_insercao', 10);
    }

    /** @test */
    public function sincronizar_produtos_evita_operacoes_desnecessarias()
    {
        $this->postJson('/api/sincronizar/produtos');

        $response = $this->postJson('/api/sincronizar/produtos');

        // na segunda chamada, nada deve ser inserido ou atualizado
        $response->assertJson([
            'inseridos'   => 0,
            'atualizados' => 0,
            'removidos'   => 0,
        ]);
    }

    /** @test */
    public function sincronizar_produtos_atualiza_registro_existente()
    {
        $this->postJson('/api/sincronizar/produtos');

        // altera dado na tabela base
        DB::table('produtos_base')
            ->where('prod_cod', 'PRD003')
            ->update(['prod_nome' => 'Monitor Atualizado']);

        $response = $this->postJson('/api/sincronizar/produtos');

        $response->assertJson(['atualizados' => 1]);

        $this->assertDatabaseHas('produto_insercao', [
            'prod_cod'  => 'PRD003',
            'prod_nome' => 'Monitor Atualizado',
        ]);
    }

    /** @test */
    public function sincronizar_produtos_remove_inativados()
    {
        $this->postJson('/api/sincronizar/produtos');

        // inativa um produto na base
        DB::table('produtos_base')
            ->where('prod_cod', 'PRD003')
            ->update(['prod_atv' => 0]);

        $this->postJson('/api/sincronizar/produtos');

        $this->assertDatabaseMissing('produto_insercao', [
            'prod_cod' => 'PRD003',
        ]);
    }

    // ==================== PREÇOS ====================

    /** @test */
    public function sincronizar_precos_retorna_200()
    {
        $response = $this->postJson('/api/sincronizar/precos');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'inseridos',
                     'atualizados',
                     'removidos',
                 ]);
    }

    /** @test */
    public function sincronizar_precos_insere_registros_na_tabela_destino()
    {
        $this->postJson('/api/sincronizar/precos');

        // precos ativos na base são 10 (PRD005 e PRD012 são inativos)
        $this->assertDatabaseCount('preco_insercao', 10);
    }

    /** @test */
    public function sincronizar_precos_nao_insere_precos_inativos()
    {
        $this->postJson('/api/sincronizar/precos');

        $this->assertDatabaseMissing('preco_insercao', [
            'prc_cod_prod' => 'PRD005',
        ]);

        $this->assertDatabaseMissing('preco_insercao', [
            'prc_cod_prod' => 'PRD012',
        ]);
    }

    /** @test */
    public function sincronizar_precos_evita_duplicidade()
    {
        $this->postJson('/api/sincronizar/precos');
        $this->postJson('/api/sincronizar/precos');

        $this->assertDatabaseCount('preco_insercao', 10);
    }

    /** @test */
    public function sincronizar_precos_evita_operacoes_desnecessarias()
    {
        $this->postJson('/api/sincronizar/precos');

        $response = $this->postJson('/api/sincronizar/precos');

        $response->assertJson([
            'inseridos'   => 0,
            'atualizados' => 0,
            'removidos'   => 0,
        ]);
    }

    /** @test */
    public function sincronizar_precos_remove_inativados()
    {
        $this->postJson('/api/sincronizar/precos');

        DB::table('precos_base')
            ->where('prc_cod_prod', 'PRD006')
            ->update(['prc_status' => 'inativo']);

        $this->postJson('/api/sincronizar/precos');

        $this->assertDatabaseMissing('preco_insercao', [
            'prc_cod_prod' => 'PRD006',
        ]);
    }

    // ==================== LISTAGEM ====================

    /** @test */
    public function listar_produtos_precos_retorna_200()
    {
        $this->postJson('/api/sincronizar/produtos');
        $this->postJson('/api/sincronizar/precos');

        $response = $this->getJson('/api/produtos-precos');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'prod_id',
                             'prod_cod',
                             'prod_nome',
                             'precos',
                         ]
                     ],
                     'current_page',
                     'per_page',
                     'total',
                 ]);
    }

    /** @test */
    public function listar_produtos_precos_aceita_parametro_per_page()
    {
        $this->postJson('/api/sincronizar/produtos');
        $this->postJson('/api/sincronizar/precos');

        $response = $this->getJson('/api/produtos-precos?per_page=5');

        $response->assertStatus(200)
                 ->assertJson(['per_page' => 5]);
    }

    /** @test */
    public function listar_produtos_precos_retorna_precos_junto()
    {
        $this->postJson('/api/sincronizar/produtos');
        $this->postJson('/api/sincronizar/precos');

        $response = $this->getJson('/api/produtos-precos');

        $data = $response->json('data');

        // verifica que pelo menos um produto tem preços
        $comPrecos = collect($data)->filter(fn($p) => count($p['precos']) > 0);
        $this->assertGreaterThan(0, $comPrecos->count());
    }
}