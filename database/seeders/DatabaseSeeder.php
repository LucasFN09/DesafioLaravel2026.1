<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Compra; // Não esqueça de importar a Compra
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->admin()->create([ //criando um admin pra teste
            'nome' => 'Admin Dev',
            'email' => 'admin@admin.com',
            'senha' => 'admin',
            'cpf' => '00000000000',
        ]);

        $nomesAdmin = [ //nomes de admins já definidos
            "Jailson Autopeças",
            "Maquita Autopeças",
            "LFN Motors",
            "Auto Bros",
            "Oficina Dois Irmãos",
            "Auto peças e Oscar alho",
            "Tião Carburadores",
            "AutoEletrica Shazam",
            "Carrara Peças"
        ];

        User::factory()->admin()->count(9)->sequence(fn ($sequence) => ['nome' => $nomesAdmin[$sequence->index]])->create()->each(function ($usuario) { 
            
            Product::factory(4)->create([ // atribui 4 produtos a cada vendedor
                'vendedor_id' => $usuario->id_usuario,
            ]);
            echo "Admin {$usuario->nome} e seus produtos criados\n";

            User::factory()->count(2)->create([ // criando 2 usuários comuns para cada admin
                'created_by' => $usuario->id_usuario
            ]);
            echo "Usuários comuns vinculados ao Admin criados\n";
        });

        // --------- CORREÇÃO NA CRIAÇÃO DE COMPRAS ---------
        $users = User::all();
        
        Compra::factory()->count(50)->make()->each(function ($compra) use ($users) {
            $buyer = $users->random();
            
            // CORREÇÃO: O campo é 'admin' (booleano true/false) e a PK é 'id_usuario'
            $seller = $users->where('admin', true)->where('id_usuario', '!=', $buyer->id_usuario)->random();
            
            // CORREÇÃO: Preenche usando as propriedades corretas do Model Compra
            $compra->id_comprador = $buyer->id_usuario;
            $compra->id_vendedor = $seller->id_usuario;
            
            $compra->save();
        });
        echo "50 Compras criadas com sucesso!\n";
    }
}