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
        Schema::create('compras_feitas', function (Blueprint $table) {
            $table->id('id_compras');
            $table->foreignId('id_produto')->constrained('produto', 'id_produto');
            $table->foreignId('id_comprador')->constrained('usuarios', 'id_usuario');
            $table->foreignId('id_vendedor')->constrained('usuarios', 'id_usuario');
            $table->decimal('valor', 10, 2);
            $table->integer('quantidade');
            $table->dateTime('data');
            $table->timestamps();
        });
    }
};
