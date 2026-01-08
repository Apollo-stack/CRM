@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4">
        
        {{-- CABEÇALHO --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-white">Lista de Clientes</h1>
            <a href="{{ route('clients.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                + Novo Cliente
            </a>
        </div>

        {{-- FILTROS E BUSCA --}}
        <div class="bg-gray-800 rounded-lg p-6 mb-6">
            <form method="GET" action="{{ route('clients.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    {{-- BUSCA GLOBAL --}}
                    <div>
                        <label class="block text-gray-400 text-sm mb-2">Buscar</label>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Nome, empresa, email, telefone..."
                               class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    {{-- FILTRO POR EMPRESA --}}
                    <div>
                        <label class="block text-gray-400 text-sm mb-2">Empresa</label>
                        <select name="company" 
                                class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">Todas as empresas</option>
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa }}" {{ request('company') == $empresa ? 'selected' : '' }}>
                                    {{ $empresa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ORDENAÇÃO --}}
                    <div>
                        <label class="block text-gray-400 text-sm mb-2">Ordenar por</label>
                        <select name="order_by" 
                                class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="name" {{ request('order_by') == 'name' ? 'selected' : '' }}>Nome</option>
                            <option value="company" {{ request('order_by') == 'company' ? 'selected' : '' }}>Empresa</option>
                            <option value="created_at" {{ request('order_by') == 'created_at' ? 'selected' : '' }}>Data de Cadastro</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                        Filtrar
                    </button>
                    <a href="{{ route('clients.index') }}" 
                       class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                        Limpar Filtros
                    </a>
                </div>
            </form>
        </div>

        {{-- TABELA DE CLIENTES --}}
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            
            @if($clients->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b-2 border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Nome</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Empresa</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Email</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Telefone</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Última Interação</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($clients as $client)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-gray-900">{{ $client->name }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $client->company_name }}</td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <a href="mailto:{{ $client->email }}" class="text-blue-600 hover:underline">
                                            {{ $client->email }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ $client->phone }}</td>
                                    <td class="px-6 py-4 text-gray-600">
                                        @if($client->ultima_interacao)
                                            <span class="text-sm">
                                                {{ $client->ultima_interacao->diffForHumans() }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">Sem interações</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('clients.show', $client->id) }}" 
                                               class="text-gray-600 hover:text-blue-600 transition">
                                                Ver
                                            </a>
                                            <a href="{{ route('clients.edit', $client->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 transition">
                                                Editar
                                            </a>
                                            <form action="{{ route('clients.destroy', $client->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Tem certeza que deseja excluir este cliente?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800 transition">
                                                    Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINAÇÃO --}}
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $clients->links() }}
                </div>

            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">Nenhum cliente encontrado.</p>
                </div>
            @endif

        </div>

        {{-- ESTATÍSTICAS RÁPIDAS --}}
        <div class="mt-6 bg-gray-800 rounded-lg p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-400 text-sm">Total de Clientes</p>
                    <p class="text-white text-2xl font-bold">{{ $clients->total() }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Mostrando</p>
                    <p class="text-white text-2xl font-bold">{{ $clients->count() }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection