<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrecoInsercao extends Model
{
    protected $table = 'preco_insercao';
    protected $primaryKey = 'preco_id';

    protected $fillable = [
        'prc_cod_prod', 'prc_valor', 'prc_moeda', 'prc_desc',
        'prc_acres', 'prc_promo', 'prc_dt_ini_promo', 'prc_dt_fim_promo',
        'prc_dt_atual', 'prc_origem', 'prc_tipo_cli', 'prc_vend_resp',
        'prc_obs', 'prc_status',
    ];

    public function produto()
    {
        return $this->belongsTo(ProdutoInsercao::class, 'prc_cod_prod', 'prod_cod');
    }
}