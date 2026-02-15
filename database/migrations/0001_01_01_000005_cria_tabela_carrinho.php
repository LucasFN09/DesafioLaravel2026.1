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
        // Tabela principal do Carrinho
        Schema::create('carrinho', function (Blueprint $table) {
            $table->id('id_carrinho');
            $table->foreignId('usuarios_id_usuario')->constrained('usuarios', 'id_usuario');
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps();
        });

        // Tabela intermediária
        Schema::create('produto_has_carrinho', function (Blueprint $table) {
            $table->foreignId('produto_id_produto')->constrained('produto', 'id_produto');
            $table->foreignId('carrinho_id_carrinho')->constrained('carrinho', 'id_carrinho');
            $table->integer('quantidade'); // Movido para aqui conforme correção
        });
    }
};
