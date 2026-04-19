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
        Schema::create('produto_insercao', function (Blueprint $table) {
            $table->id('prod_id');
            $table->string('prod_cod', 30)->unique();
            $table->string('prod_nome', 150)->nullable();
            $table->string('prod_cat', 50)->nullable();
            $table->string('prod_subcat', 50)->nullable();
            $table->text('prod_desc')->nullable();
            $table->string('prod_fab', 100)->nullable();
            $table->string('prod_mod', 50)->nullable();
            $table->string('prod_cor', 30)->nullable();
            $table->string('prod_peso', 30)->nullable();
            $table->string('prod_larg', 30)->nullable();
            $table->string('prod_alt', 30)->nullable();
            $table->string('prod_prof', 30)->nullable();
            $table->string('prod_und', 10)->nullable();
            $table->boolean('prod_atv')->default(true);
            $table->string('prod_dt_cad', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produto_insercao');
    }
};
