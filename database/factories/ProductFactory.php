<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $categorias = [
            'Motor e Performance', 'Pneus e Rodas', 'Som e Vídeo', 
            'Iluminação', 'Óleos e Fluidos', 'Freios e Suspensão'
        ];

        return [
            'nome' => $this->faker->words(3, true),
            'descricao' => $this->faker->paragraph(),
            'preco' => $this->faker->randomFloat(2, 50, 5000),
            'categoria' => $this->faker->randomElement($categorias),
            'estoque' => $this->faker->numberBetween(1, 100),
            'foto' => null,
            'vendedor_id' => User::factory(), 
        ];
    }
}