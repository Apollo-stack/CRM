<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meus Clientes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-700">Lista de Clientes</h3>
                        <a href="{{ route('clients.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                            + Novo Cliente
                        </a>
                    </div>

                    {{-- Mensagem de Sucesso (aparece quando você salva) --}}
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Tabela de Dados --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Nome</th>
                                    <th class="py-3 px-6 text-left">Empresa</th>
                                    <th class="py-3 px-6 text-left">Telefone</th>
                                    <th class="py-3 px-6 text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                {{-- Aqui acontece a mágica: um loop para cada cliente --}}
                                @forelse($clients as $client)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="py-3 px-6 text-left whitespace-nowrap font-medium">
                                            {{ $client->name }}
                                        </td>
                                        <td class="py-3 px-6 text-left">
                                            {{ $client->company_name ?? '-' }}
                                        </td>
                                        <td class="py-3 px-6 text-left">
                                            {{ $client->phone ?? '-' }}
                                        </td>
                                        
                                        <td class="py-3 px-6 text-center flex justify-center gap-2">
                                            {{-- Botão VER (Onde a mágica acontece) --}}
                                            <a href="{{ route('clients.show', $client->id) }}" class="text-gray-500 hover:text-gray-700 font-bold border border-gray-200 px-3 py-1 rounded">
                                                Ver
                                            </a>
                                        
                                            {{-- Botão Editar --}}
                                            <a href="{{ route('clients.edit', $client->id) }}" class="text-blue-500 hover:text-blue-700 font-bold border border-blue-200 px-3 py-1 rounded">
                                                Editar
                                            </a>

                                            {{-- Botão Excluir (Precisa ser um formulário para segurança) --}}
                                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" 
                                                onsubmit="return confirm('Tem certeza que deseja excluir este cliente?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold border border-red-200 px-3 py-1 rounded">
                                                    Excluir
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-gray-500">
                                            Nenhum cliente encontrado. Cadastre o primeiro!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>