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
    Schema::create('produto', function (Blueprint $table) {
        $table->id('id_produto');
        $table->foreignId('vendedor_id')->constrained('usuarios', 'id_usuario');
        $table->string('nome');
        $table->text('descricao')->nullable();
        $table->string('foto')->nullable();
        $table->decimal('preco', 10, 2);
        $table->integer('estoque')->default(0);
        
        $table->enum('categoria', [
            'Motor e Performance', 'Pneus e Rodas', 'Som e Vídeo', 
            'Iluminação', 'Óleos e Fluidos', 'Freios e Suspensão', 
            'Acessórios Internos', 'Acessórios Externos', 'Ferramentas', 'Outros'
        ])->nullable();

        $table->timestamps();
    });
}
};
