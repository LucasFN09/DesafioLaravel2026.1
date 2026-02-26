<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Bem-vindo {{ auth()->user()->nome }}</h1>
        @if(auth()->user()->admin)
            <p class="text-gray-700 mb-4">Você é um administrador. Use o painel de controle para gerenciar produtos e usuários.</p>
            <a href="{{ route('admin_produtos') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700 transition">
                Gerenciar Produtos
            </a>
            <a href="{{ route('admin_usuarios') }}" class="inline-block bg-green-600 text-white px-6 py-2 rounded font-bold hover:bg-green-700 transition ml-4">
                Gerenciar Usuários
            </a>
            <a href="{{ route('perfil_pessoal') }}" class="inline-block bg-purple-600 text-white px-6 py-2 rounded font-bold hover:bg-purple-700 transition ml-4">
                Meu Perfil
            </a>
        @else
            <p class="text-gray-700 mb-4">Você é um usuário comum. Explore os produtos disponíveis e aproveite as ofertas!</p>
            <a href="{{ route('historico') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700 transition">
                Ver Histórico de Compras
            </a>
            <a href="{{ route('perfil_pessoal') }}" class="inline-block bg-purple-600 text-white px-6 py-2 rounded font-bold hover:bg-purple-700 transition ml-4">
                Meu Perfil
            </a>
        @endif


    </div>
</x-app-layout>
