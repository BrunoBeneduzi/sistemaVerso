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
        Schema::create('preco_insercao', function (Blueprint $table) {
            $table->id('preco_id');
            $table->string('prc_cod_prod', 30)->index();
            $table->decimal('prc_valor', 10, 2)->nullable();
            $table->string('prc_moeda', 10)->nullable();
            $table->string('prc_desc', 10)->nullable();
            $table->string('prc_acres', 10)->nullable();
            $table->decimal('prc_promo', 10, 2)->nullable();
            $table->string('prc_dt_ini_promo', 30)->nullable();
            $table->string('prc_dt_fim_promo', 30)->nullable();
            $table->string('prc_dt_atual', 30)->nullable();
            $table->string('prc_origem', 50)->nullable();
            $table->string('prc_tipo_cli', 30)->nullable();
            $table->string('prc_vend_resp', 100)->nullable();
            $table->text('prc_obs')->nullable();
            $table->string('prc_status', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preco_insercao');
    }
};
