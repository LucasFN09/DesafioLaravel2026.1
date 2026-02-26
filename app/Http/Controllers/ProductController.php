<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class ProductController extends Controller
{
    public function index(Request $request)
    {

        $query = Product::with('vendedor');

        if (Auth::check()) {
            $query->where('vendedor_id', '!=', Auth::id());
        }

        if ($request->filled('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        $produtos = $query->latest()->paginate(6)->withQueryString();

        return view('welcome', compact('produtos'));
    }

    public function show($id)
    {
        $produto = Product::with('vendedor')->findOrFail($id);
        return view('produto_individual', compact('produto'));
    }
}