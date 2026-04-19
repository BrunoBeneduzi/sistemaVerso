<?php

namespace App\Services;

use App\Models\ViewProduto;
use App\Models\ViewPreco;
use App\Models\ProdutoInsercao;
use App\Models\PrecoInsercao;

class SincronizacaoService
{
    public function sincronizarProdutos(): array
    {
        $itens = ViewProduto::all();
        $inseridos = 0;
        $atualizados = 0;

        // remove da tabela destino o que não está mais ativo na view
        $codsAtivos = $itens->pluck('prod_cod')->toArray();
        $removidos = ProdutoInsercao::whereNotIn('prod_cod', $codsAtivos)->delete();

        foreach ($itens as $item) {
            $existe = ProdutoInsercao::where('prod_cod', $item->prod_cod)->first();

            $dados = [
                'prod_nome'   => $item->prod_nome,
                'prod_cat'    => $item->prod_cat,
                'prod_subcat' => $item->prod_subcat,
                'prod_desc'   => $item->prod_desc,
                'prod_fab'    => $item->prod_fab,
                'prod_mod'    => $item->prod_mod,
                'prod_cor'    => $item->prod_cor,
                'prod_peso'   => $item->prod_peso,
                'prod_larg'   => $item->prod_larg,
                'prod_alt'    => $item->prod_alt,
                'prod_prof'   => $item->prod_prof,
                'prod_und'    => $item->prod_und,
                'prod_atv'    => $item->prod_atv,
                'prod_dt_cad' => $item->prod_dt_cad,
            ];

            if ($existe) {
                // evita operação desnecessária
                if ($existe->only(array_keys($dados)) !== $dados) {
                    $existe->update($dados);
                    $atualizados++;
                }
            } else {
                ProdutoInsercao::create(
                    array_merge(['prod_cod' => $item->prod_cod], $dados)
                );
                $inseridos++;
            }
        }

        return [
            'message'     => 'Produtos sincronizados com sucesso!',
            'inseridos'   => $inseridos,
            'atualizados' => $atualizados,
            'removidos'   => $removidos,
        ];
    }

    public function sincronizarPrecos(): array
    {
        $itens = ViewPreco::all();
        $inseridos = 0;
        $atualizados = 0;

        $codsAtivos = $itens->pluck('prc_cod_prod')->toArray();
        $removidos = PrecoInsercao::whereNotIn('prc_cod_prod', $codsAtivos)->delete();

        foreach ($itens as $item) {
            $existe = PrecoInsercao::where('prc_cod_prod', $item->prc_cod_prod)->first();

            $dados = [
                'prc_valor'        => $item->prc_valor,
                'prc_moeda'        => $item->prc_moeda,
                'prc_desc'         => $item->prc_desc,
                'prc_acres'        => $item->prc_acres,
                'prc_promo'        => $item->prc_promo,
                'prc_dt_ini_promo' => $item->prc_dt_ini_promo,
                'prc_dt_fim_promo' => $item->prc_dt_fim_promo,
                'prc_dt_atual'     => $item->prc_dt_atual,
                'prc_origem'       => $item->prc_origem,
                'prc_tipo_cli'     => $item->prc_tipo_cli,
                'prc_vend_resp'    => $item->prc_vend_resp,
                'prc_obs'          => $item->prc_obs,
                'prc_status'       => $item->prc_status,
            ];

            if ($existe) {
                if ($existe->only(array_keys($dados)) !== $dados) {
                    $existe->update($dados);
                    $atualizados++;
                }
            } else {
                PrecoInsercao::create(
                    array_merge(['prc_cod_prod' => $item->prc_cod_prod], $dados)
                );
                $inseridos++;
            }
        }

        return [
            'message'     => 'Preços sincronizados com sucesso!',
            'inseridos'   => $inseridos,
            'atualizados' => $atualizados,
            'removidos'   => $removidos,
        ];
    }

    public function listarProdutosComPrecos(int $porPagina = 10)
    {
        return ProdutoInsercao::with('precos')->paginate($porPagina);
    }
}