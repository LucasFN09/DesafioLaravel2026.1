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
                            + Anunciar Novo Produto
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
                                        <div class="w-12 h-12 overflow-hidden rounded shadow-sm">
                                            <img src="{{ $produto->foto ?? 'https://via.placeholder.com/150' }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">{{ $produto->nome }}</td>
                                    <td class="px-4 py-2 text-green-600 font-bold">{{ formatar_preco($produto->preco) }}</td>
                                    <td class="px-4 py-2 text-center space-x-2">
                                        <button onclick="carregarDadosModal('{{ $produto->id_produto }}', 'visualizar')" class="text-blue-500 hover:underline">Ver</button>
                                        <button onclick="carregarDadosModal('{{ $produto->id_produto }}', 'editar')" class="text-yellow-500 hover:underline">Editar</button>
                                        <button onclick="confirmarExclusao('{{ $produto->id_produto }}')" class="text-red-500 hover:underline">Excluir</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">Nenhum produto encontrado.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $produtos->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalProduto" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-6">
            <h3 id="modalTitulo" class="text-xl font-bold mb-4">Adicionar Produto</h3>

            <form id="formProduto" method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div id="metodoPut"></div>
                <div class="space-y-4">
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
                            <select name="categoria" id="inputCategoria" required class="w-full rounded-md dark:bg-gray-700">
                                <option value="">Selecione</option>
                                @foreach($categorias as $categoria)
                                <option value="{{ $categoria }}">{{ $categoria }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Foto</label>
                        <div class="mb-2">
                            <img id="previaFoto"
                                class="hidden w-[120px] h-[150px] object-cover rounded border-2 border-blue-500 shadow-sm">
                        </div>
                        <input type="file" name="foto" id="inputFoto" accept="image/*" class="...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Descrição</label>
                        <textarea name="descricao" id="inputDescricao" rows="3" class="w-full rounded-md border-gray-300 dark:bg-gray-700"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="fecharModal('modalProduto')" class="px-4 py-2">Cancelar</button>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-bold">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalVisualizar" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full max-h-[90vh] overflow-y-auto shadow-2xl">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white" id="visuNome"></h3>

            <div class="w-full flex justify-center bg-gray-100 dark:bg-gray-900 rounded-lg p-2 mb-4">
                <img id="visuFoto" src=""
                    class="w-[250px] h-[300px] object-cover rounded-md shadow-sm border border-gray-200 dark:border-gray-700">
            </div>

            <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                <p><strong class="text-gray-900 dark:text-white">Preço:</strong> <span id="visuPreco"></span></p>
                <p><strong class="text-gray-900 dark:text-white">Categoria:</strong> <span id="visuCategoria"></span></p>
                <div>
                    <strong class="text-gray-900 dark:text-white">Descrição:</strong>
                    <p id="visuDescricao" class="mt-1 text-gray-500 dark:text-gray-400 italic leading-relaxed"></p>
                </div>
            </div>

            <button onclick="fecharModal('modalVisualizar')"
                class="mt-6 w-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold py-2 rounded-lg transition">
                Fechar
            </button>
        </div>
    </div>

    <div id="modalExcluir" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-sm w-full max-h-[80vh] overflow-y-auto text-center">
            <h3 class="text-lg font-bold text-red-600 mb-4">Excluir Produto?</h3>
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
        const getEl = (id) => document.getElementById(id);

        window.carregarDadosModal = function(id, tipo) {
            fetch(`/admin_produtos/dados/${id}`)
                .then(res => res.json())
                .then(produto => {
                    tipo === 'editar' ? abrirEdicao(produto) : abrirVisualizacao(produto);
                })
                .catch(() => alert('Erro ao carregar dados.'));
        }

        function abrirEdicao(produto) {
            const form = getEl('formProduto');
            getEl('modalTitulo').innerText = "Editar Peça";
            form.action = `/admin_produtos/${produto.id_produto}`;
            getEl('metodoPut').innerHTML = `<input type="hidden" name="_method" value="PUT">`;

            getEl('inputNome').value = produto.nome;
            getEl('inputPreco').value = produto.preco;
            getEl('inputCategoria').value = produto.categoria;
            getEl('inputDescricao').value = produto.descricao || '';

            if (produto.foto) {
                getEl('previaFoto').src = produto.foto;
                getEl('previaFoto').classList.remove('hidden');
            } else {
                getEl('previaFoto').classList.add('hidden');
            }
            getEl('modalProduto').classList.remove('hidden');
        }

        function abrirVisualizacao(produto) {
            getEl('visuNome').innerText = produto.nome;
            getEl('visuFoto').src = produto.foto || 'https://via.placeholder.com/300';
            getEl('visuPreco').innerText = 'R$ ' + parseFloat(produto.preco).toLocaleString('pt-br', {
                minimumFractionDigits: 2
            });
            getEl('visuCategoria').innerText = produto.categoria;
            getEl('visuDescricao').innerText = produto.descricao || 'Sem descrição.';
            getEl('modalVisualizar').classList.remove('hidden');
        }

        window.abrirModalCriar = function() {
            const form = getEl('formProduto');
            getEl('modalTitulo').innerText = "Anunciar Nova Peça";
            form.action = "/admin_produtos";
            form.reset();
            getEl('metodoPut').innerHTML = "";
            getEl('previaFoto').classList.add('hidden');
            getEl('modalProduto').classList.remove('hidden');
        }

        window.confirmarExclusao = function(id) {
            getEl('formExcluir').action = `/admin_produtos/${id}`;
            getEl('modalExcluir').classList.remove('hidden');
        }

        window.fecharModal = (id) => getEl(id).classList.add('hidden');
    </script>
</x-app-layout>