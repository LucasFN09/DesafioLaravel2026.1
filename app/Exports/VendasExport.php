<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VendasExport implements FromCollection, WithHeadings, WithMapping
{
    protected $vendas;

    public function __construct($vendas) {
        $this->vendas = $vendas;
    }

    public function collection() {
        return $this->vendas;
    }

    public function headings(): array {
        return ["Data", "Produto", "Categoria", "Valor", "Comprador", "Vendedor"];
    }

    public function map($venda): array {
        return [
            $venda->data->format('d/m/Y H:i'),
            $venda->produto->nome ?? 'N/A',
            $venda->produto->categoria ?? 'N/A',
            $venda->valor,
            $venda->comprador->nome ?? 'N/A',
            $venda->vendedor->nome ?? 'N/A',
        ];
    }
}