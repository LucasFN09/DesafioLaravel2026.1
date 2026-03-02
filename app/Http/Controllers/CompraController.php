<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\VendasExport;
use Maatwebsite\Excel\Facades\Excel;

class CompraController extends Controller
{
    public function store(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->admin) {
            return redirect()->back()->with('error', 'Apenas usuários comuns podem comprar.');
        }

        $request->validate([
            'quantidade' => 'required|integer|min:1|max:999',
        ]);

        $produto = Product::findOrFail($id);
        $comprador = Auth::user();
        $quantidade = $request->quantidade;
        $valorTotal = $produto->preco * $quantidade;

        if ($comprador->saldo < $valorTotal) {
            return redirect()->back()->with('error', 'Saldo insuficiente para realizar a compra.');
        }

        Compra::create([
            'id_produto' => $produto->id_produto,
            'id_comprador' => $comprador->id_usuario,
            'id_vendedor' => $produto->vendedor_id,
            'valor' => $produto->preco,
            'quantidade' => $quantidade,
            'data' => now(),
        ]);

        User::where('id_usuario', $comprador->id_usuario)->decrement('saldo', $valorTotal);
        User::where('id_usuario', $produto->vendedor_id)->increment('saldo', $valorTotal);

        return redirect()->route('historico')->with('success', 'Compra realizada com sucesso!');
    }

    public function history(Request $request)
    {
        return $this->purchaseHistory($request);
    }

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