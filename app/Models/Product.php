<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $table = 'produto';
    protected $primaryKey = 'id_produto';

    protected $fillable = [
        'vendedor_id',
        'nome',
        'descricao',
        'categoria',
        'preco',
        'foto',
    ];

    protected $casts = [
        'preco' => 'decimal:2'
    ];

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendedor_id', 'id_usuario');
    }

    public static function categorias()
    {
        return ['Motor e Performance','Pneus e Rodas','Som e Vídeo','Iluminação','Óleos e Fluidos','Freios e Suspensão','Acessórios Internos','Acessórios Externos','Ferramentas','Outros'];
    }

    
}