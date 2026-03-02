<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $tipo == 'vendas' ? __('Meus Produtos Vendidos') : __('Meus Produtos Comprados') }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('vendas.pdf', array_merge(request()->query(), ['tipo' => $tipo])) }}" target="_blank" class="bg-red-600 text-white px-4 py-2 rounded shadow">
                    PDF
                </a>
                @if($tipo == 'vendas' && auth()->user()->admin)
                <a href="{{ route('vendas.excel', request()->query()) }}" class="bg-green-600 text-white px-4 py-2 rounded shadow">
                    Excel (XLSX)
                </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 mb-6 rounded-lg shadow">
                <form action="{{ url()->current() }}" method="GET" class="flex items-end gap-4">
                    <div class="flex gap-4">
                        <input type="date" name="data_inicio" value="{{ request('data_inicio') }}" class="rounded dark:bg-gray-700 dark:text-white">
                        <input type="date" name="data_fim" value="{{ request('data_fim') }}" class="rounded dark:bg-gray-700 dark:text-white">
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Filtrar</button>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="px-4 py-2">Data</th>
                                <th class="px-4 py-2">Produto</th>
                                <th class="px-4 py-2">Qtd.</th>
                                <th class="px-4 py-2">Preço Unit.</th>
                                <th class="px-4 py-2">Total</th>
                                <th class="px-4 py-2">
                                    {{ $tipo == 'vendas' ? 'Comprador' : 'Vendedor' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-900 dark:text-gray-100">
                            @forelse($registros as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $item->data->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3">{{ $item->produto->nome ?? 'Produto Removido' }}</td>
                                <td class="px-4 py-3">{{ $item->quantidade }}</td>
                                <td class="px-4 py-3">R$ {{ number_format($item->valor, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 font-bold">R$ {{ number_format($item->valor * $item->quantidade, 2, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    {{ $tipo == 'vendas' ? ($item->comprador->nome ?? 'N/A') : ($item->vendedor->nome ?? 'N/A') }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="p-4 text-center">Nenhum registro encontrado.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $registros->appends(request()->query())->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>