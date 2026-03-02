<?php

namespace Database\Factories;

use App\Models\Compra;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompraFactory extends Factory
{
    protected $model = Compra::class;

    public function definition(): array
    {
        // Pega produtos e usuários aleatórios
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $buyer = User::inRandomOrder()->first() ?? User::factory()->create();
        
        //Busca usando 'id_usuario' e garante que o vendedor seja Admin
        $seller = User::where('admin', true)
                     ->where('id_usuario', '!=', $buyer->id_usuario)
                     ->inRandomOrder()
                     ->first() ?? User::factory()->admin()->create();

        $quantity = $this->faker->numberBetween(1, 5);
        $totalPrice = $quantity * $product->preco; // Calcula o valor total com base no preço do produto

        return [
            'id_comprador' => $buyer->id_usuario,
            'id_produto'   => $product->id_produto,
            'id_vendedor'  => $seller->id_usuario,
            'quantidade'   => $quantity,
            'valor'        => $totalPrice, 
            'data'         => $this->faker->dateTimeThisYear(),
        ];
    }
}