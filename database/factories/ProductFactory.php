<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{


public function definition(): array
    {

        /*$categoria = [
            'Motor e Performance','Pneus e Rodas','Som e Vídeo','Iluminação','Óleos e Fluidos',
            'Freios e Suspensão','Acessórios Internos','Acessórios Externos','Ferramentas','Outros'
        ];*/

        $nomes = [
            'Filtro de Ar Esportivo', 'Kit de Suspensão Rebaixada', 'Rodas de Liga Leve 17"', 
            'Sistema de Som Automotivo', 'Lâmpadas LED para Farol', 'Óleo Sintético 5W30' , "Kit Turbo Propulsor", 
            "Pneus de Alta Performance", "Bateria de Longa Duração", "Freios de Cerâmica", "Suspensão a Ar", "Rodas Esportivas 18", 
            "Sistema de Escape Esportivo", "Luzes de Neon para Carro", "Óleo de Motor Premium", "Acessórios Internos Personalizados", "Acessórios Externos para Carro", 
            "Chave de Boca", "Chave de Roda", "Tomada automotiva", "Macaco Hidráulico", "Suporte para Celular no Carro", "Câmera de Ré", "Sensor de Estacionamento", "Alarme Automotivo", 
            "Rastreador Veicular", "Tinta para Retoque de Carro", "Capa para Banco de Carro", "Tapetes Personalizados para Carro", "Protetor de Para-choque", "Organizador de Porta-malas"
        ];
        
        // escolhe um nome e usa-o também para inferir a categoria
        $nomeEscolhido = $this->faker->randomElement($nomes);

        return [
            'nome' => $nomeEscolhido,
            'descricao' => $this->faker->paragraph(),
            'preco' => $this->faker->randomFloat(2, 50, 5000),
            'categoria' => $this->associaCategoria($nomeEscolhido),
            'estoque' => $this->faker->numberBetween(1, 100),
            'foto' => null,
            'vendedor_id' => User::factory()->create()->id_usuario, 
        ];
    }

    private function associaCategoria($nome): string
    {
        if($nome){
            if ($this->containsAny($nome, ['Filtro', 'Turbo', 'Bateria', 'Escape'])) {
                return 'Motor e Performance';
            } elseif ($this->containsAny($nome, ['Rodas', 'Pneus'])) {
                return 'Pneus e Rodas';
            } elseif ($this->containsAny($nome, ['Som', 'Vídeo', 'Luzes'])) {
                return 'Som e Vídeo';
            } elseif ($this->containsAny($nome, ['Lâmpadas', 'Iluminação', 'Neon'])) {
                return 'Iluminação';
            } elseif ($this->containsAny($nome, ['Óleo'])) {
                return 'Óleos e Fluidos';
            } elseif ($this->containsAny($nome, ['Freios', 'Suspensão'])) {
                return 'Freios e Suspensão';
            } elseif ($this->containsAny($nome, ['Acessórios Internos', 'Internos', 'Tapetes'])) {
                return 'Acessórios Internos';
            } elseif ($this->containsAny($nome, ['Acessórios Externos', 'Externos', 'Câmera', 'Sensor', 'Tinta'])) {
                return 'Acessórios Externos';
            } elseif ($this->containsAny($nome, ['Chave', 'Tomada', 'Macaco'])) {
                return 'Ferramentas';
            }
        }     
        return 'Outros';
    }

    private function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($haystack, $needle)) {
                return true;
            }
        }
        return false;
    }


}