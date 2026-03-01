<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // ESTA É A FUNÇÃO QUE ESTÁ FALTANDO:
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

    // Página individual do produto
    public function show($id)
    {
        $produto = Product::with('vendedor')->findOrFail($id);
        return view('produto_individual', compact('produto'));
    }

    // --- MÉTODOS DE GERENCIAMENTO (ADMIN) ---

    public function adminIndex()
    {
        $produtos = Product::where('vendedor_id', Auth::id())->latest()->paginate(10);
        $categorias = Product::categorias();
        return view('admin_produtos', compact('produtos', 'categorias'));
    }

    // Rota AJAX que você pediu
    public function getDados($id)
    {
        $produto = Product::findOrFail($id);

        if ($produto->vendedor_id !== Auth::id()) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        return response()->json($produto);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric',
            'categoria' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $dados = $request->except('foto');
        $dados['vendedor_id'] = Auth::id();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('produtos', 'public');
            $dados['foto'] = '/storage/' . $path;
        }

        Product::create($dados);

        return redirect()->route('admin_produtos')->with('success', 'Produto anunciado!');
    }

    public function update(Request $request, $id)
    {
        $produto = Product::findOrFail($id);
        if ($produto->vendedor_id !== Auth::id()) abort(403);

        $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric',
            'categoria' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $dados = $request->only(['nome', 'preco', 'categoria', 'descricao']);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('produtos', 'public');
            $dados['foto'] = '/storage/' . $path;
        }

        $produto->update($dados); // Agora usamos a variável $dados com a foto nova

        return redirect()->route('admin_produtos')->with('success', 'Produto atualizado!');
    }

    public function destroy($id)
    {
        $produto = Product::findOrFail($id);
        if ($produto->vendedor_id !== Auth::id()) abort(403);

        $produto->delete();
        return redirect()->route('admin_produtos')->with('success', 'Produto excluído!');
    }
}
