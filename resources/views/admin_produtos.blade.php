<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gerenciar Minhas Peças') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="mb-6">
                        <button onclick="abrirModalCriar()" class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700 transition">
                            + Anunciar Nova Peça
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 dark:border-gray-600">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left">Foto</th>
                                    <th class="px-4 py-2 text-left">Nome</th>
                                    <th class="px-4 py-2 text-left">Preço</th>
                                    <th class="px-4 py-2 text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produtos as $produto)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
                                    <td class="px-4 py-2">
                                        <img src="{{ $produto->foto }}" class="w-10 h-10 object-cover rounded">
                                    </td>
                                    <td class="px-4 py-2">{{ $produto->nome }}</td>
                                    <td class="px-4 py-2 text-green-600 font-bold">{{ formatar_preco($produto->preco) }}</td>
                                    <td class="px-4 py-2 text-center space-x-2">
                                        <button onclick="abrirModalVisualizar({{ json_encode($produto) }})" class="text-blue-500 hover:underline">Ver</button>
                                        <button onclick="abrirModalEditar({{ json_encode($produto) }})" class="text-yellow-500 hover:underline">Editar</button>
                                        <button onclick="confirmarExclusao('{{ $produto->id_produto }}')" class="text-red-500 hover:underline">Excluir</button>
                                    </td>
                                </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-4">Nenhum produto encontrado.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalProduto" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <h3 id="modalTitulo" class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Anunciar Peça</h3>
                
                <form id="formProduto" method="POST" action="{{ route('produtos.store') }}">
                    @csrf
                    <div id="metodoPut"></div> <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium">Nome do Produto</label>
                            <input type="text" name="nome" id="inputNome" required class="w-full rounded-md border-gray-300 dark:bg-gray-700">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Preço</label>
                                <input type="number" name="preco" id="inputPreco" step="0.01" required class="w-full rounded-md border-gray-300 dark:bg-gray-700">
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Categoria</label>
                                <select name="categoria" id="inputCategoria" class="w-full rounded-md border-gray-300 dark:bg-gray-700">
                                    <option value="Motor">Motor</option>
                                    <option value="Suspensão">Suspensão</option>
                                    <option value="Acessórios">Acessórios</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Link da Foto (URL)</label>
                            <input type="text" name="foto" id="inputFoto" class="w-full rounded-md border-gray-300 dark:bg-gray-700">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Descrição</label>
                            <textarea name="descricao" id="inputDescricao" rows="3" class="w-full rounded-md border-gray-300 dark:bg-gray-700"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="fecharModal('modalProduto')" class="px-4 py-2 text-gray-500">Cancelar</button>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-bold">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalVisualizar" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full">
            <h3 class="text-xl font-bold mb-4" id="visuNome"></h3>
            <img id="visuFoto" class="w-full h-48 object-cover rounded mb-4">
            <div class="space-y-2 text-sm">
                <p><strong>Preço:</strong> <span id="visuPreco"></span></p>
                <p><strong>Categoria:</strong> <span id="visuCategoria"></span></p>
                <p><strong>Descrição:</strong></p>
                <p id="visuDescricao" class="text-gray-600 dark:text-gray-400 italic"></p>
            </div>
            <button onclick="fecharModal('modalVisualizar')" class="mt-6 w-full bg-gray-200 py-2 rounded">Fechar</button>
        </div>
    </div>

    <div id="modalExcluir" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-sm w-full text-center">
            <h3 class="text-lg font-bold text-red-600 mb-4">Excluir Produto?</h3>
            <p class="mb-6">Esta ação é permanente.</p>
            <form id="formExcluir" method="POST">
                @csrf @method('DELETE')
                <div class="flex justify-center gap-4">
                    <button type="button" onclick="fecharModal('modalExcluir')" class="text-gray-500">Voltar</button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Sim, Excluir</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modalProduto = document.getElementById('modalProduto');
        const formProduto = document.getElementById('formProduto');
        const metodoPut = document.getElementById('metodoPut');

        function abrirModalCriar() {
            document.getElementById('modalTitulo').innerText = "Anunciar Peça";
            formProduto.action = "{{ route('produtos.store') }}";
            formProduto.reset();
            metodoPut.innerHTML = ""; // Limpa o PUT
            modalProduto.classList.remove('hidden');
        }

        function abrirModalEditar(produto) {
            document.getElementById('modalTitulo').innerText = "Editar Peça";
            formProduto.action = `/produtos/${produto.id_produto}`;
            metodoPut.innerHTML = `<input type="hidden" name="_method" value="PUT">`;
            
            // Preenche os campos
            document.getElementById('inputNome').value = produto.nome;
            document.getElementById('inputPreco').value = produto.preco;
            document.getElementById('inputCategoria').value = produto.categoria;
            document.getElementById('inputFoto').value = produto.foto;
            document.getElementById('inputDescricao').value = produto.descricao;

            modalProduto.classList.remove('hidden');
        }

        function abrirModalVisualizar(produto) {
            document.getElementById('visuNome').innerText = produto.nome;
            document.getElementById('visuFoto').src = produto.foto || 'https://via.placeholder.com/300';
            document.getElementById('visuPreco').innerText = 'R$ ' + parseFloat(produto.preco).toLocaleString('pt-br', {minimumFractionDigits: 2});
            document.getElementById('visuCategoria').innerText = produto.categoria;
            document.getElementById('visuDescricao').innerText = produto.descricao || 'Sem descrição.';
            
            document.getElementById('modalVisualizar').classList.remove('hidden');
        }

        function confirmarExclusao(id) {
            document.getElementById('formExcluir').action = `/produtos/${id}`;
            document.getElementById('modalExcluir').classList.remove('hidden');
        }

        function fecharModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>
</x-app-layout>