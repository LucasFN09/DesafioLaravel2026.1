<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nome',
        'email',
        'senha', // No banco usamos 'senha'
        'telefone',
        'data_nascimento',
        'cpf',
        'saldo',
        'foto',
        'admin',
        'created_by',
    ];

    protected $hidden = [
        'senha',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'senha' => 'hashed',
            'data_nascimento' => 'date',
            'admin' => 'boolean',
            'saldo' => 'decimal:2',
        ];
    }

    /**
     * O Laravel busca por 'password' para logar. 
     * Como usamos 'senha', precisamos avisar o sistema:
     */
    public function getAuthPassword()
    {
        return $this->senha;
    }

    // Quem criou
    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id_usuario');
    }

    // Se admin, que produtos vende 
    public function produtos(): HasMany
    {
        return $this->hasMany(Product::class, 'vendedor_id', 'id_usuario');
    }

    // Endereços do usuário
    public function enderecos(): HasMany
    {
        return $this->hasMany(Endereco::class, 'usuario_id', 'id_usuario');
    }

    public function ComprasUsuario(): HasMany 
    {
        return $this->hasMany(Compra::class, 'id_comprador', 'id_usuario');
    }

    public function VendasAdmin(): HasMany 
    {
        return $this->hasMany(Compra::class, 'id_vendedor', 'id_usuario');
    }
}