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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('senha');
            $table->string('telefone')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('cpf')->unique()->nullable();
            $table->decimal('saldo', 10, 2)->default(0);
            $table->string('foto')->nullable();
            $table->boolean('admin')->default(false);
            
            // Autorreferência (Admin que criou o utilizador)
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id_usuario')->on('usuarios');
            
            $table->timestamps();
        });
    }
};
