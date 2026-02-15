<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'senha' => Hash::make('senha'), 
            'cpf' => $this->faker->cpf(false),
            'telefone' => $this->faker->cellphoneNumber(),
            'data_nascimento' => $this->faker->date(),
            'saldo' => $this->faker->randomFloat(2, 0, 1000),
            'admin' => false, //0 comum, 1 admin (cria usuarios comuns por padrão)
            'created_by' => null,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'admin' => true,
            'saldo' => 0.00,
            'created_by' => null, //GARANTE Q FICA NULL (segurança nunca é demais)
        ]);
    }

}
