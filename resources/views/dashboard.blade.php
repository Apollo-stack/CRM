<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Grid de 3 Cartões de Métricas --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                {{-- Cartão 1: Total de Clientes --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 dark:text-gray-400 mb-2">Total de Clientes</div>
                    <div class="text-4xl font-bold text-gray-800 dark:text-white">
                        {{ $totalClients }}
                    </div>
                </div>

                {{-- Cartão 2: Negócios Ativos --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-gray-500 dark:text-gray-400 mb-2">Negócios em Aberto</div>
                    <div class="text-4xl font-bold text-gray-800 dark:text-white">
                        {{ $activeLeads }}
                    </div>
                </div>

                {{-- Cartão 3: Total Vendido --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500 dark:text-gray-400 mb-2">Total Vendido (R$)</div>
                    <div class="text-4xl font-bold text-green-600 dark:text-green-400">
                        R$ {{ number_format($wonValue, 2, ',', '.') }}
                    </div>
                </div>

            </div>

            {{-- Cartão Extra: Atalhos Rápidos --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 mb-4">Acesso Rápido</h3>
                <div class="flex gap-4">
                    <a href="{{ route('clients.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        + Cadastrar Cliente
                    </a>
                    <a href="{{ route('leads.create') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        + Novo Negócio
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>