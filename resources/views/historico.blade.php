<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Histórico de Compras') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 dark:border-gray-600">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left">Data</th>
                                    <th class="px-4 py-2 text-left">Produto</th>
                                    <th class="px-4 py-2 text-left">Valor</th>
                                    <th class="px-4 py-2 text-left">Qtd.</th>
                                    <th class="px-4 py-2 text-left">Vendedor</th>
                                    @if(auth()->user()->admin)
                                        <th class="px-4 py-2 text-left">Comprador</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($compras as $compra)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
                                        <td class="px-4 py-2">{{ $compra->data->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-2">{{ optional($compra->produto)->nome }}</td>
                                        <td class="px-4 py-2 text-green-600 font-bold">{{ formatar_preco($compra->valor) }}</td>
                                        <td class="px-4 py-2">{{ $compra->quantidade }}</td>
                                        <td class="px-4 py-2">{{ optional($compra->vendedor)->nome }}</td>
                                        @if(auth()->user()->admin)
                                            <td class="px-4 py-2">{{ optional($compra->comprador)->nome }}</td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="@if(auth()->user()->admin)6 @else 5 @endif" class="text-center py-4">Nenhuma compra registrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $compras->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
