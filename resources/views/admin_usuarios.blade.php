<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gerenciar Usuários') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if(Auth::user()->admin)
                    <div class="mb-6">
                        <button onclick="abrirModalCriar()" class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700 transition">
                            + Novo Usuário
                        </button>
                    </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 dark:border-gray-600">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left">Foto</th>
                                    <th class="px-4 py-2 text-left">Nome</th>
                                    <th class="px-4 py-2 text-left">E-mail</th>
                                    <th class="px-4 py-2 text-left">CPF</th>
                                    <th class="px-4 py-2 text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usuarios as $user)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
                                    <td class="px-4 py-2">
                                        <div class="w-10 h-10 overflow-hidden rounded-full shadow-sm">
                                            <img src="{{ $user->foto ?? 'https://ui-avatars.com/api/?name='.urlencode($user->nome) }}" class="w-full h-full object-cover">
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">{{ $user->nome }} {!! $user->admin ? '<span class="text-xs bg-red-500 text-white px-2 py-1 rounded ml-2">Admin</span>' : '' !!}</td>
                                    <td class="px-4 py-2">{{ $user->email }}</td>
                                    <td class="px-4 py-2">{{ $user->cpf }}</td>
                                    <td class="px-4 py-2 text-center space-x-2">
                                        <button onclick="carregarDadosModal('{{ $user->id_usuario }}', 'visualizar')" class="text-blue-500 hover:underline">Ver</button>
                                        <button onclick="carregarDadosModal('{{ $user->id_usuario }}', 'editar')" class="text-yellow-500 hover:underline">Editar</button>
                                        <button onclick="confirmarExclusao('{{ $user->id_usuario }}')" class="text-red-500 hover:underline">Excluir</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Nenhum usuário encontrado.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $usuarios->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalUsuario" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto p-6">
            <h3 id="modalTitulo" class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Adicionar Usuário</h3>

            <form id="formUsuario" method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div id="metodoPut"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h4 class="font-semibold text-lg border-b pb-2">Dados Pessoais</h4>
                        
                        <div>
                            <label class="block text-sm font-medium">Nome</label>
                            <input type="text" name="nome" id="inputNome" required class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">E-mail</label>
                            <input type="email" name="email" id="inputEmail" required class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Senha <span class="text-xs text-gray-400" id="avisoSenha"></span></label>
                            <input type="password" name="senha" id="inputSenha" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-sm font-medium">CPF</label>
                                <input type="text" name="cpf" id="inputCpf" required class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Data Nasc.</label>
                                <input type="date" name="data_nascimento" id="inputDataNasc" required class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-sm font-medium">Telefone</label>
                                <input type="text" name="telefone" id="inputTelefone" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Saldo (R$)</label>
                                <input type="number" name="saldo" id="inputSaldo" step="0.01" value="0.00" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Foto</label>
                            <input type="file" name="foto" id="inputFoto" accept="image/*" class="w-full text-sm">
                        </div>
                        @if(Auth::user()->admin)
                        <div class="flex items-center mt-2">
                            <input type="checkbox" name="admin" id="inputAdmin" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm">
                            <label class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Dar privilégios de Administrador</label>
                        </div>
                        @endif
                    </div>

                    <div class="space-y-4">
                        <h4 class="font-semibold text-lg border-b pb-2">Endereço</h4>
                        
                        <div class="grid grid-cols-3 gap-2">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium">CEP</label>
                                <input type="text" name="cep" id="inputCep" onblur="buscarCep()" required class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Somente números">
                                <span id="cepLoading" class="text-xs text-blue-500 hidden">Buscando CEP...</span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Número</label>
                                <input type="text" name="numero" id="inputNumero" required class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Logradouro (Rua)</label>
                            <input type="text" name="logradouro" id="inputLogradouro" readonly class="w-full rounded-md border-gray-300 bg-gray-100 dark:bg-gray-600 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Complemento</label>
                            <input type="text" name="complemento" id="inputComplemento" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Bairro</label>
                            <input type="text" name="bairro" id="inputBairro" readonly class="w-full rounded-md border-gray-300 bg-gray-100 dark:bg-gray-600 dark:text-white">
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium">Cidade</label>
                                <input type="text" name="cidade" id="inputCidade" readonly class="w-full rounded-md border-gray-300 bg-gray-100 dark:bg-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Estado</label>
                                <input type="text" name="estado" id="inputEstado" readonly class="w-full rounded-md border-gray-300 bg-gray-100 dark:bg-gray-600 dark:text-white">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="fecharModal('modalUsuario')" class="px-4 py-2 text-gray-600 dark:text-gray-300">Cancelar</button>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalVisualizar" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex items-center space-x-4 mb-6">
                <img id="visuFoto" src="" class="w-20 h-20 rounded-full object-cover border-2 border-blue-500">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="visuNome"></h3>
                    <p class="text-sm text-gray-500" id="visuEmail"></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-300 mb-6">
                <p><strong class="dark:text-white">CPF:</strong> <span id="visuCpf"></span></p>
                <p><strong class="dark:text-white">Telefone:</strong> <span id="visuTelefone"></span></p>
                <p><strong class="dark:text-white">Nascimento:</strong> <span id="visuNasc"></span></p>
                <p><strong class="dark:text-white">Saldo:</strong> R$ <span id="visuSaldo"></span></p>
            </div>

            <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-md text-sm text-gray-700 dark:text-gray-300">
                <strong class="block mb-1 dark:text-white">Endereço Principal:</strong>
                <span id="visuEnderecoCompleto"></span>
            </div>

            <button onclick="fecharModal('modalVisualizar')" class="mt-6 w-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold py-2 rounded-lg transition">
                Fechar
            </button>
        </div>
    </div>

    <div id="modalExcluir" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-sm w-full max-h-[80vh] overflow-y-auto text-center">
            <h3 class="text-lg font-bold text-red-600 mb-4">Excluir Usuário?</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-6">Esta ação apagará todos os dados vinculados a ele.</p>
            <form id="formExcluir" method="POST">
                @csrf @method('DELETE')
                <div class="flex justify-center gap-4">
                    <button type="button" onclick="fecharModal('modalExcluir')" class="text-gray-500 hover:underline">Cancelar</button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded font-bold hover:bg-red-700">Sim, Excluir</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const getEl = (id) => document.getElementById(id);

        // FUNÇÃO DE BUSCA DO VIACEP VIA NOSSA API INTERNA
        window.buscarCep = function() {
            let cep = getEl('inputCep').value.replace(/\D/g, '');
            if (cep.length === 8) {
                getEl('cepLoading').classList.remove('hidden');
                fetch(`/api/cep/${cep}`)
                    .then(res => res.json())
                    .then(data => {
                        getEl('cepLoading').classList.add('hidden');
                        if (!data.erro) {
                            getEl('inputLogradouro').value = data.logradouro || '';
                            getEl('inputBairro').value = data.bairro || '';
                            getEl('inputCidade').value = data.localidade || '';
                            getEl('inputEstado').value = data.uf || '';
                            getEl('inputNumero').focus();
                        } else {
                            alert("CEP não encontrado.");
                        }
                    })
                    .catch(() => {
                        getEl('cepLoading').classList.add('hidden');
                        alert("Erro ao consultar CEP.");
                    });
            }
        }

        window.carregarDadosModal = function(id, tipo) {
            fetch(`/admin_usuarios/dados/${id}`)
                .then(res => res.json())
                .then(usuario => {
                    tipo === 'editar' ? abrirEdicao(usuario) : abrirVisualizacao(usuario);
                })
                .catch(() => alert('Erro ao carregar dados.'));
        }

        function abrirEdicao(usuario) {
            const form = getEl('formUsuario');
            getEl('modalTitulo').innerText = "Editar Usuário";
            form.action = `/admin_usuarios/${usuario.id_usuario}`;
            getEl('metodoPut').innerHTML = `<input type="hidden" name="_method" value="PUT">`;
            
            // Tratamento da senha: não é obrigatória na edição
            getEl('inputSenha').required = false;
            getEl('avisoSenha').innerText = "(Deixe em branco para não alterar)";

            getEl('inputNome').value = usuario.nome;
            getEl('inputEmail').value = usuario.email;
            getEl('inputCpf').value = usuario.cpf;
            getEl('inputDataNasc').value = usuario.data_nascimento ? usuario.data_nascimento.split('T')[0] : '';
            getEl('inputTelefone').value = usuario.telefone || '';
            getEl('inputSaldo').value = usuario.saldo;
            
            if(getEl('inputAdmin')) getEl('inputAdmin').checked = usuario.admin;

            // Preenche o endereço se existir
            if (usuario.enderecos && usuario.enderecos.length > 0) {
                const end = usuario.enderecos[0];
                getEl('inputCep').value = end.cep || '';
                getEl('inputNumero').value = end.numero || '';
                getEl('inputLogradouro').value = end.logradouro || '';
                getEl('inputComplemento').value = end.complemento || '';
                getEl('inputBairro').value = end.bairro || '';
                getEl('inputCidade').value = end.cidade || '';
                getEl('inputEstado').value = end.estado || '';
            } else {
                // Limpa os campos de endereço se não tiver
                ['Cep', 'Numero', 'Logradouro', 'Complemento', 'Bairro', 'Cidade', 'Estado'].forEach(campo => getEl('input' + campo).value = '');
            }

            getEl('modalUsuario').classList.remove('hidden');
        }

        function abrirVisualizacao(usuario) {
            getEl('visuNome').innerText = usuario.nome;
            getEl('visuEmail').innerText = usuario.email;
            getEl('visuCpf').innerText = usuario.cpf;
            getEl('visuTelefone').innerText = usuario.telefone || 'Não informado';
            
            if(usuario.data_nascimento) {
                const data = new Date(usuario.data_nascimento);
                getEl('visuNasc').innerText = data.toLocaleDateString('pt-BR', {timeZone: 'UTC'});
            } else {
                getEl('visuNasc').innerText = 'Não informado';
            }
            
            getEl('visuSaldo').innerText = parseFloat(usuario.saldo).toLocaleString('pt-br', {minimumFractionDigits: 2});
            getEl('visuFoto').src = usuario.foto || `https://ui-avatars.com/api/?name=${encodeURIComponent(usuario.nome)}`;

            // Formata o endereço para exibição
            if (usuario.enderecos && usuario.enderecos.length > 0) {
                const end = usuario.enderecos[0];
                getEl('visuEnderecoCompleto').innerText = `${end.logradouro}, ${end.numero} ${end.complemento ? ' - ' + end.complemento : ''} - ${end.bairro}, ${end.cidade} - ${end.estado} (CEP: ${end.cep})`;
            } else {
                getEl('visuEnderecoCompleto').innerText = "Nenhum endereço cadastrado.";
            }

            getEl('modalVisualizar').classList.remove('hidden');
        }

        window.abrirModalCriar = function() {
            const form = getEl('formUsuario');
            getEl('modalTitulo').innerText = "Novo Usuário";
            form.action = "/admin_usuarios";
            form.reset();
            
            getEl('metodoPut').innerHTML = "";
            getEl('inputSenha').required = true;
            getEl('avisoSenha').innerText = "";
            
            getEl('modalUsuario').classList.remove('hidden');
        }

        window.confirmarExclusao = function(id) {
            getEl('formExcluir').action = `/admin_usuarios/${id}`;
            getEl('modalExcluir').classList.remove('hidden');
        }

        window.fecharModal = (id) => getEl(id).classList.add('hidden');
    </script>
</x-app-layout>