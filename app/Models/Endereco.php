<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Endereco extends Model
{
    use HasFactory;

    protected $table = 'endereco';
    protected $primaryKey = 'id_endereco';

    protected $fillable = [
        'usuarios_id_usuario',
        'cep',
        'numero',
        'logradouro',
        'bairro',
        'cidade',
        'estado',
        'complemento',
    ];

    /**
     * Relacionamento: O endereço pertence a um utilizador.
     */
    public function usuario(): BelongsTo
    {
        // Especificamos a FK 'usuarios_id_usuario' e a PK da tabela usuarios 'id_usuario'
        return $this->belongsTo(User::class, 'usuarios_id_usuario', 'id_usuario');
    }
}