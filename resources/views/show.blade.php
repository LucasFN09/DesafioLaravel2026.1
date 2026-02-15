<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalhes: {{ $produto->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <img src="{{ $produto->foto ?? 'https://via.placeholder.com/500' }}" class="w-full rounded-lg shadow-md">
                </div>
                <div>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">{{ $produto->categoria }}</span>
                    <h1 class="text-4xl font-bold mt-4">{{ $produto->nome }}</h1>
                    <p class="text-2xl text-green-600 font-bold mt-2">{{ formatar_preco($produto->preco) }}</p>
                    
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-bold text-gray-700">Descrição:</h3>
                        <p class="text-gray-600 mt-2">{{ $produto->descricao }}</p>
                    </div>

                    <div class="mt-6">
                        <p><strong>Vendedor:</strong> {{ $produto->vendedor->nome }}</p>
                    </div>

                    <button class="w-full bg-blue-600 text-white mt-8 py-3 rounded-lg font-bold hover:bg-blue-700">
                        Adicionar ao Carrinho
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>