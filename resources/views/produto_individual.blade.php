<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalhes do Produto
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <a href="{{ route('home') }}" class="inline-flex items-center mb-4 text-gray-600 hover:text-blue-600 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Voltar a HomePage
            </a>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                    
                    <div class="p-6 bg-gray-50 flex items-center justify-center border-b md:border-b-0 md:border-r border-gray-200">
                        @if($produto->foto)
                            <img src="{{ $produto->foto }}" alt="{{ $produto->nome }}" class="max-h-96 w-full object-contain rounded-lg shadow-sm">
                        @else
                            <div class="flex flex-col items-center justify-center text-gray-400 h-64 w-full bg-gray-200 rounded-lg">
                                <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>Sem foto disponível</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-8 flex flex-col justify-between">
                        <div>
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold tracking-wide uppercase mb-2">
                                {{ $produto->categoria }}
                            </span>

                            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
                                {{ $produto->nome }}
                            </h1>

                            <div class="flex items-baseline mb-6">
                                <span class="text-4xl font-bold text-green-600">
                                    {{ formatar_preco($produto->preco) }}
                                </span>
                            </div>

                            <div class="mb-6">
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Descrição Técnica</h3>
                                <div class="text-gray-600 leading-relaxed whitespace-pre-line">
                                    {{ $produto->descricao }}
                                </div>
                            </div>

                            <hr class="border-gray-200 my-6">

                            <div class="mb-6">
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Informações do Anunciante</h3>
                                <div class="flex items-center gap-4">
                                    <div class="bg-blue-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg">
                                        {{ substr($produto->vendedor->nome, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-lg">{{ $produto->vendedor->nome }}</p>
                                        <p class="text-gray-500 flex items-center mt-1">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            {{ $produto->vendedor->telefone ?? 'Telefone não informado' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            @auth
                                @if(Auth::user()->admin)
                                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative text-center" role="alert">
                                        <strong class="font-bold block">Ação restrita!</strong>
                                        <span class="block sm:inline">Administradores não podem realizar compras.</span>
                                    </div>
                                @else
                                        <form action="{{ route('compra', $produto->id_produto) }}" method="POST">
                                            @csrf
                                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-4 rounded-lg shadow-lg transform transition hover:scale-105 flex justify-center items-center gap-2">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            COMPRAR AGORA
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>