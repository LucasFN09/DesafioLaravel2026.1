<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Endereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    // Lista os usuários na tabela
    public function adminIndex(Request $request)
    {
        $filter = $request->query('filter');

        // Se for admin, vê todos (mas com regras específicas). Se não for, vê apenas ele mesmo (RF005)
        if (Auth::user()->admin) {
            $query = User::latest();

            // admin só vê usuários comuns + admins que ele criou + ele mesmo
            $query->where(function($q) {
                $q->where('admin', false)
                  ->orWhere('created_by', Auth::id())
                  ->orWhere('id_usuario', Auth::id());
            });

            // Aplica filtro se fornecido
            if ($filter === 'admin') {
                // também respeita a regra acima pois já filtramos
                $query->where('admin', true);
            } elseif ($filter === 'comum') {
                $query->where('admin', false);
            }

            $usuarios = $query->paginate(10);
        } else {
            $usuarios = User::where('id_usuario', Auth::id())->paginate(1);
        }

        return view('admin_usuarios', compact('usuarios', 'filter'));
    }

    // Retorna os dados via AJAX para preencher os modais (incluindo o endereço)
    public function getDados($id)
    {
        // usarmos findOrFail nos dá exceção automática em caso de id inválido;
        // com o relacionamento corrigido conseguimos carregar o endereço.
        $usuario = User::with('enderecos')->find($id);

        if (!$usuario) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        // Verifica permissão
        if (!Auth::user()->admin) {
            // user comum só vê ele mesmo
            if ($usuario->id_usuario !== Auth::id()) {
                return response()->json(['error' => 'Não autorizado'], 403);
            }
        } else {
            // admin: só acessa outros admins criados por ele ou quaisquer usuários comuns
            if ($usuario->admin && $usuario->id_usuario !== Auth::id() && $usuario->created_by !== Auth::id()) {
                return response()->json(['error' => 'Não autorizado'], 403);
            }
        }

        return response()->json($usuario);
    }

    // Rota da API interna que busca no ViaCEP
    public function consultaCep($cep)
    {
        // Remove tudo que não for número
        $cepLimpo = preg_replace('/[^0-9]/', '', $cep);

        $response = Http::get("https://viacep.com.br/ws/{$cepLimpo}/json/");

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json(['erro' => true], 404);
    }

    // Criação de Usuário e Endereço
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'senha' => 'required|min:6',
            'cpf' => 'required|unique:usuarios,cpf',
            'cep' => 'required|string',
            'numero' => 'required|string',
            'foto' => 'nullable|image|max:2048'
        ]);

        $dadosUsuario = $request->only(['nome', 'email', 'telefone', 'data_nascimento', 'cpf', 'saldo']);
        $dadosUsuario['senha'] = Hash::make($request->senha);
        $dadosUsuario['admin'] = $request->has('admin') ? true : false;

        // Se o admin logado estiver criando, ele é o 'created_by'
        $dadosUsuario['created_by'] = Auth::id();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('usuarios', 'public');
            $dadosUsuario['foto'] = '/storage/' . $path;
        }

        // Cria o Usuário
        $user = User::create($dadosUsuario);

        // Cria o Endereço vinculado
        Endereco::create([
            'usuarios_id_usuario' => $user->id_usuario,
            'cep' => $request->cep,
            'numero' => $request->numero,
            'logradouro' => $request->logradouro,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
            'complemento' => $request->complemento,
        ]);

        return redirect()->route('admin_usuarios')->with('success', 'Usuário criado com sucesso!');
    }

    // Edição de Usuário e Endereço
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        if (Auth::user()->admin) {
            // admin só pode alterar usuário comum ou admin criado por ele ou a si mesmo
            if ($usuario->admin && $usuario->id_usuario !== Auth::id() && $usuario->created_by !== Auth::id()) {
                abort(403);
            }
        } else {
            if ($usuario->id_usuario !== Auth::id()) abort(403);
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . $id . ',id_usuario', // Ignora o próprio email na validação
            'cpf' => 'required|unique:usuarios,cpf,' . $id . ',id_usuario',
            'cep' => 'required|string',
            'numero' => 'required|string',
        ]);

        $dadosUsuario = $request->only(['nome', 'email', 'telefone', 'data_nascimento', 'cpf', 'saldo']);

        // Só atualiza a senha se o campo for preenchido
        if ($request->filled('senha')) {
            $dadosUsuario['senha'] = Hash::make($request->senha);
        }

        // Apenas admin pode mudar o status de admin de alguém
        if (Auth::user()->admin && Auth::id() !== $usuario->id_usuario) {
            $dadosUsuario['admin'] = $request->has('admin') ? true : false;
        }

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('usuarios', 'public');
            $dadosUsuario['foto'] = '/storage/' . $path;
        }

        $usuario->update($dadosUsuario);

        // Atualiza ou cria o endereço
        Endereco::updateOrCreate(
            ['usuarios_id_usuario' => $usuario->id_usuario],
            $request->only(['cep', 'numero', 'logradouro', 'bairro', 'cidade', 'estado', 'complemento'])
        );

        return redirect()->route('admin_usuarios')->with('success', 'Usuário atualizado!');
    }

    // Exclusão
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        if (Auth::user()->admin) {
            if ($usuario->admin && $usuario->id_usuario !== Auth::id() && $usuario->created_by !== Auth::id()) {
                abort(403);
            }
        } else {
            if ($usuario->id_usuario !== Auth::id()) abort(403);
        }

        // Opcional: deletar endereços vinculados antes se o banco não tiver "ON DELETE CASCADE"
        Endereco::where('usuarios_id_usuario', $id)->delete();

        $usuario->delete();
        return redirect()->route('admin_usuarios')->with('success', 'Usuário excluído!');
    }

    // Atualiza perfil pessoal
    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . $user->id_usuario . ',id_usuario',
            'telefone' => 'nullable|string',
            'data_nascimento' => 'nullable|date',
            'cep' => 'nullable|string',
            'numero' => 'nullable|string',
            'logradouro' => 'nullable|string',
            'bairro' => 'nullable|string',
            'cidade' => 'nullable|string',
            'estado' => 'nullable|string',
            'complemento' => 'nullable|string',
        ]);

        $userUpdates = $request->only(['nome','email','telefone','data_nascimento']);
        $user->update($userUpdates);

        // cria/atualiza endereço
        if ($request->filled('cep') || $request->filled('numero')) {
            Endereco::updateOrCreate(
                ['usuarios_id_usuario' => $user->id_usuario],
                $request->only(['cep','numero','logradouro','bairro','cidade','estado','complemento'])
            );
        }

        return redirect()->route('perfil_pessoal')->with('success','Perfil atualizado!');
    }
}
