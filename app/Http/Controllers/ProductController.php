<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Iniciamos a consulta
        $query = Product::with('vendedor');

        // RESTRIÇÃO: Apenas produtos de OUTROS usuários
        // Auth::id() pega automaticamente a chave primária (id_usuario) do logado
        if (Auth::check()) {
            $query->where('vendedor_id', '!=', Auth::id());
        }

        // BARRA DE PESQUISA: Filtro por nome
        if ($request->filled('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%');
        }

        // FILTRO POR CATEGORIA
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // PAGINAÇÃO: 6 por página, mantendo os filtros na URL
        $produtos = $query->latest()->paginate(6)->withQueryString();

        return view('welcome', compact('produtos'));
    }

    public function show($id)
    {
        $produto = Product::with('vendedor')->findOrFail($id);
        return view('produtos.show', compact('produto'));
    }
}