<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutoInsercao extends Model
{
    protected $table = 'produto_insercao';
    protected $primaryKey = 'prod_id';

    protected $fillable = [
        'prod_cod', 'prod_nome', 'prod_cat', 'prod_subcat',
        'prod_desc', 'prod_fab', 'prod_mod', 'prod_cor',
        'prod_peso', 'prod_larg', 'prod_alt', 'prod_prof',
        'prod_und', 'prod_atv', 'prod_dt_cad',
    ];

    public function precos()
    {
        return $this->hasMany(PrecoInsercao::class, 'prc_cod_prod', 'prod_cod');
    }
}