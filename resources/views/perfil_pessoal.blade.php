<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Meu Perfil') }}
        </h2>
        <div class="mt-8 flex justify-center gap-4">
            <a href="{{ route('historico') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Histórico de Compras</a>
            <a href="{{ route('dashboard') }}" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700">Dashboard</a>
            <a href="{{ url('/') }}" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Página Inicial</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('perfil_pessoal.update') }}" method="POST">
                    @csrf
                    <div class="flex flex-col items-center mb-6">
                        <div class="w-24 h-24 rounded-full overflow-hidden shadow-sm mb-4">
                            <img src="{{ auth()->user()->foto ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->nome) }}" class="w-full h-full object-cover">
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            <input type="text" name="nome" value="{{ auth()->user()->nome }}" class="text-center bg-transparent font-bold focus:outline-none text-gray-900 dark:text-white">
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <input type="email" name="email" value="{{ auth()->user()->email }}" class="bg-transparent focus:outline-none text-black">
                        </p>
                    </div>

                    <div class="space-y-4 text-gray-700 dark:text-gray-300">
                        <div><strong>CPF:</strong> {{ auth()->user()->cpf }}</div>
                        <div>
                            <label class="font-medium">Telefone:</label>
                            <input type="text" name="telefone" value="{{ auth()->user()->telefone }}" class="ml-2 bg-transparent focus:outline-none text-black">
                        </div>
                        <div>
                            <label class="font-medium">Data de Nascimento:</label>
                            <input type="date" name="data_nascimento" value="{{ auth()->user()->data_nascimento?auth()->user()->data_nascimento->format('Y-m-d'):'' }}" class="ml-2 bg-transparent focus:outline-none text-black">
                        </div>
                        <div><strong>Saldo:</strong> R$ {{ number_format(auth()->user()->saldo,2,',','.') }}</div>
                    </div>

                <div class="mt-8 bg-gray-50 dark:bg-gray-700 p-4 rounded">
                    <h4 class="font-semibold mb-2 text-gray-900 dark:text-white">Endereço</h4>
                    @php
                    $end = auth()->user()->enderecos->first();
                    @endphp
                    <div class="space-y-2">
                        <div>
                            <label class="block text-sm font-medium">CEP</label>
                            <input type="text" name="cep" id="cepPerfil" value="{{ $end->cep ?? '' }}" onblur="buscarCepPerfil()" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Somente números">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Logradouro</label>
                            <input type="text" name="logradouro" id="logradouroPerfil" value="{{ $end->logradouro ?? '' }}" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Número</label>
                            <input type="text" name="numero" id="numeroPerfil" value="{{ $end->numero ?? '' }}" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Complemento</label>
                            <input type="text" name="complemento" id="complementoPerfil" value="{{ $end->complemento ?? '' }}" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Bairro</label>
                            <input type="text" name="bairro" id="bairroPerfil" value="{{ $end->bairro ?? '' }}" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Cidade</label>
                            <input type="text" name="cidade" id="cidadePerfil" value="{{ $end->cidade ?? '' }}" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Estado</label>
                            <input type="text" name="estado" id="estadoPerfil" value="{{ $end->estado ?? '' }}" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                        </div>
                        <span id="cepLoadingPerfil" class="text-xs text-blue-500 hidden">Buscando CEP...</span>
                        @if(empty($end))
                        <p class="text-sm text-red-500">Seu endereço ainda não foi cadastrado. Insira o CEP acima para preencher automaticamente.</p>
                        @endif
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Salvar Alterações</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                </form>
    <script>
        const getEl = id => document.getElementById(id);

        function buscarCepPerfil() {
            let cep = getEl('cepPerfil').value.replace(/\D/g, '');
            if (cep.length === 8) {
                getEl('cepLoadingPerfil').classList.remove('hidden');
                fetch(`/api/cep/${cep}`)
                    .then(res => res.json())
                    .then(data => {
                        getEl('cepLoadingPerfil').classList.add('hidden');
                        if (!data.erro) {
                            getEl('logradouroPerfil').value = data.logradouro || '';
                            getEl('bairroPerfil').value = data.bairro || '';
                            getEl('cidadePerfil').value = data.localidade || '';
                            getEl('estadoPerfil').value = data.uf || '';
                        } else {
                            alert('CEP não encontrado.');
                        }
                    })
                    .catch(() => {
                        getEl('cepLoadingPerfil').classList.add('hidden');
                        alert('Erro ao consultar CEP.');
                    });
            }
        }
    </script>
</x-app-layout>