<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras_feitas';
    protected $primaryKey = 'id_compras';
    
    public $timestamps = false;

    protected $fillable = [
        'id_produto',
        'id_comprador',
        'id_vendedor',
        'valor',
        'data',
        'quantidade', 
    ];

    protected $casts = [
        'data' => 'datetime',
        'valor' => 'decimal:2',
        'quantidade' => 'integer',
    ];

    /**
     * Relacionamento: A compra refere-se a um produto.
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_produto', 'id_produto');
    }

    /**
     * Relacionamento: Quem comprou o produto.
     */
    public function comprador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_comprador', 'id_usuario');
    }

    /**
     * Relacionamento: Quem vendeu o produto.
     */
    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_vendedor', 'id_usuario');
    }
}