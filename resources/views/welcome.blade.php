<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AutoPeças</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-100">
    <nav class="bg-white shadow mb-8">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-blue-600">AutoPeças</a>
            <div>
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-gray-700">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 mr-4">Entrar</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Cadastrar</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Peças Disponíveis</h1>

    <div class="mb-8 bg-white p-4 rounded-lg shadow-sm border">
        <form action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="O que você procura?" 
                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            
            <select name="categoria" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Todas as Categorias</option>
                <option value="Motor e Performance" {{ request('categoria') == 'Motor e Performance' ? 'selected' : '' }}>Motor e Performance</option>
                <option value="Pneus e Rodas" {{ request('categoria') == 'Pneus e Rodas' ? 'selected' : '' }}>Pneus e Rodas</option>
                <option value="Iluminação" {{ request('categoria') == 'Iluminação' ? 'selected' : '' }}>Iluminação</option>
                <option value="Som e Vídeo" {{ request('categoria') == 'Som e Vídeo' ? 'selected' : '' }}>Som e Vídeo</option>
                <option value="Óleos e Fluidos" {{ request('categoria') == 'Óleos e Fluidos' ? 'selected' : '' }}>Óleos e Fluidos</option>
                <option value="Freios e Suspensão" {{ request('categoria') == 'Freios e Suspensão' ? 'selected' : '' }}>Freios e Suspensão</option>
                <option value="Acessórios Internos" {{ request('categoria') == 'Acessórios Internos' ? 'selected' : '' }}>Acessórios Internos</option>
                <option value="Acessórios Externos" {{ request('categoria') == 'Acessórios Externos' ? 'selected' : '' }}>Acessórios Externos</option>
                <option value="Ferramentas" {{ request('categoria') == 'Ferramentas' ? 'selected' : '' }}>Ferramentas</option>
                <option value="Outros" {{ request('categoria') == 'Outros' ? 'selected' : '' }}>Outros</option>    
            </select>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700">
                Pesquisar
            </button>
            
            @if(request()->anyFilled(['search', 'categoria']))
                <a href="{{ route('home') }}" class="text-red-500 flex items-center text-sm underline">Limpar</a>
            @endif
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($produtos as $produto)
            <div class="p-4 bg-white shadow rounded-lg flex flex-col justify-between">
                <div>
                    <img src="{{ $produto->foto ?? 'https://via.placeholder.com/500' }}" class="w-full h-48 object-cover rounded-lg mb-4">
                    <h1 class="text-xl font-bold">{{ $produto->nome }}</h1>
                    <p class="text-gray-600 text-sm mb-2">{{ Str::limit($produto->descricao, 80) }}</p>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">{{ $produto->categoria }}</span>
                    
                    <p class="text-green-600 font-bold text-xl mt-3">
                        {{ formatar_preco($produto->preco) }}
                    </p>
                    <p class="text-xs text-gray-400">Vendedor: {{ $produto->vendedor->nome }}</p>
                </div>
                
                <div class="mt-4 flex flex-col gap-2">
                    <a href="{{ route('produto.show', $produto->id_produto) }}" class="text-center text-blue-500 underline text-sm">
                        Ver Detalhes
                    </a>

                    @auth
                        @if(!auth()->user()->admin)
                            <a href="#" class="block text-center bg-green-500 text-white py-2 rounded font-bold hover:bg-green-600">
                                Comprar
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 text-lg">Nenhuma peça encontrada.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8 mb-12">
        {{ $produtos->links() }}
    </div>
</main>
</body>
</html>