<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CompraController extends Controller
{
    /**
     * Registra uma nova compra.
     */
    public function store($idProduto)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        if ($user->admin) {
            return redirect()->back()->with('error', 'Administradores não podem efetuar compras.');
        }

        $produto = Product::findOrFail($idProduto);

        // não pode comprar seus próprios produtos
        if ($produto->vendedor_id === $user->id_usuario) {
            return redirect()->back()->with('error', 'Você não pode comprar sua própria peça.');
        }

        // verifica saldo
        if ($user->saldo < $produto->preco) {
            return redirect()->back()->with('error', 'Saldo insuficiente para a compra.');
        }

        // débito do comprador
        $user->saldo -= $produto->preco;
        $user->saldo;

        // crédito no vendedor
        $vendedor = $produto->vendedor;
        if ($vendedor) {
            $vendedor->saldo += $produto->preco;
            $vendedor->save();
        }

        // gravação da compra
        Compra::create([
            'id_produto' => $produto->id_produto,
            'id_comprador' => $user->id_usuario,
            'id_vendedor' => $produto->vendedor_id,
            'valor' => $produto->preco,
            'data' => Carbon::now(),
            'quantidade' => 1,
        ]);

        return redirect()->route('historico')->with('success', 'Compra realizada com sucesso!');
    }

    /**
     * Exibe o histórico de compras do usuário (ou todas para admin).
     */
    public function history()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        if ($user->admin) {
            $compras = Compra::with(['produto', 'comprador', 'vendedor'])
                ->latest()
                ->paginate(10);
        } else {
            $compras = Compra::with(['produto', 'vendedor'])
                ->where('id_comprador', $user->id_usuario)
                ->latest()
                ->paginate(10);
        }

        return view('historico', compact('compras'));
    }
}
