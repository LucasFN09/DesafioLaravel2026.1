<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\VendasExport;
use Maatwebsite\Excel\Facades\Excel;

class CompraController extends Controller
{
    public function purchaseHistory(Request $request)
    {
        $user = Auth::user();
        // Filtra onde o ID do comprador é o ID do usuário logado
        $query = Compra::with(['produto', 'vendedor'])
            ->where('id_comprador', $user->id_usuario);

        $this->applyFilters($query, $request);

        $registros = $query->orderBy('data', 'desc')->paginate(10);
        
        return view('historico', ['registros' => $registros, 'tipo' => 'compras']);
    }

    public function salesHistory(Request $request)
    {
        $user = Auth::user();
        $query = Compra::with(['produto', 'comprador']);

        // Se não for admin, filtra apenas onde o ID do vendedor é o do logado
        if (!$user->admin) {
            $query->where('id_vendedor', $user->id_usuario);
        }

        $this->applyFilters($query, $request);

        $registros = $query->orderBy('data', 'desc')->paginate(10);

        return view('historico', ['registros' => $registros, 'tipo' => 'vendas']);
    }

    private function applyFilters($query, $request)
    {
        if ($request->filled('data_inicio')) {
            $query->whereDate('data', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->whereDate('data', '<=', $request->data_fim);
        }
    }
}