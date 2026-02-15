<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        
    User::factory()->admin()->create([ //criando um admin pra teste (mais fáciil acesso)
            'nome' => 'Admin Dev',
            'email' => 'admin@admin.com',
            'senha' => 'admin',
            'cpf' => '00000000000',
        ]);

        User::factory()->admin()->count(9)->create()->each(function ($usuario) { //essa função divide os 36 produtos e 18 usuario entre os 9 admins e chama todas factories enquanto cria os admins(confuso, eu sei, mais foi o jeito q eu consegui pensar por enquanto) 
            Product::factory(4)->create([ //já atribui 4 produtos a cada vendedor
                'vendedor_id' => $usuario->id_usuario, 
                ]);
                echo "Admins e produtos criados\n";

            User::factory()->count(2)->create([ //criando 2 usuários comuns para cada admin
                'created_by' => $usuario->id_usuario
            ]); 
                echo "Usuários comuns criados\n";

            });
             // *dexar o admin dev sem usuários por enquanto

    }
}
