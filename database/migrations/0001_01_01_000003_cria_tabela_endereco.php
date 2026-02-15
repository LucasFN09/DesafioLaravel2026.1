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
        Schema::create('endereco', function (Blueprint $table) {
            $table->id('id_endereco');
            $table->foreignId('usuarios_id_usuario')->constrained('usuarios', 'id_usuario')->onDelete('cascade');
            $table->string('cep', 45);
            $table->string('numero', 45);
            $table->string('logradouro');
            $table->string('bairro');
            $table->string('cidade');
            $table->string('estado');
            $table->string('complemento')->nullable();
            $table->timestamps();
        });
    }
};
