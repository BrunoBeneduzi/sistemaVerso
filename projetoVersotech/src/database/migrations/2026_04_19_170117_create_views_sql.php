<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW view_produtos AS
            SELECT
                prod_id,
                TRIM(UPPER(prod_cod))               AS prod_cod,
                TRIM(REPLACE(prod_nome,'  ',' '))   AS prod_nome,
                TRIM(UPPER(prod_cat))               AS prod_cat,
                TRIM(UPPER(prod_subcat))            AS prod_subcat,
                TRIM(prod_desc)                     AS prod_desc,
                TRIM(prod_fab)                      AS prod_fab,
                TRIM(prod_mod)                      AS prod_mod,
                TRIM(UPPER(prod_cor))               AS prod_cor,
                TRIM(LOWER(prod_peso))              AS prod_peso,
                TRIM(LOWER(prod_larg))              AS prod_larg,
                TRIM(LOWER(prod_alt))               AS prod_alt,
                TRIM(LOWER(prod_prof))              AS prod_prof,
                TRIM(UPPER(prod_und))               AS prod_und,
                prod_atv,
                prod_dt_cad
            FROM produtos_base
            WHERE prod_atv = 1
        ");

        DB::statement("
            CREATE VIEW view_precos AS
            SELECT
                preco_id,
                TRIM(UPPER(prc_cod_prod))           AS prc_cod_prod,
                TRIM(prc_valor)                     AS prc_valor,
                TRIM(UPPER(prc_moeda))              AS prc_moeda,
                TRIM(prc_desc)                      AS prc_desc,
                TRIM(prc_acres)                     AS prc_acres,
                TRIM(prc_promo)                     AS prc_promo,
                TRIM(prc_dt_ini_promo)              AS prc_dt_ini_promo,
                TRIM(prc_dt_fim_promo)              AS prc_dt_fim_promo,
                TRIM(prc_dt_atual)                  AS prc_dt_atual,
                TRIM(UPPER(prc_origem))             AS prc_origem,
                TRIM(UPPER(prc_tipo_cli))           AS prc_tipo_cli,
                TRIM(prc_vend_resp)                 AS prc_vend_resp,
                TRIM(prc_obs)                       AS prc_obs,
                TRIM(LOWER(prc_status))             AS prc_status
            FROM precos_base
            WHERE TRIM(LOWER(prc_status)) = 'ativo'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_precos');
        DB::statement('DROP VIEW IF EXISTS view_produtos');
    }
};
