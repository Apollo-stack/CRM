@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Clientes</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Gerencie sua base de contatos e empresas.</p>
            </div>
            <div class="flex gap-3 w-full md:w-auto">
                <a href="{{ route('clients.trash') }}" 
                   class="flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm"
                   title="Lixeira">
                    <span class="mr-2">🗑️</span> Lixeira
                </a>
                <a href="{{ route('clients.create') }}" 
                   class="flex items-center justify-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition shadow-md hover:shadow-lg w-full md:w-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Novo Cliente
                </a>
            </div>
        </div>

        {{-- BARRA DE FERRAMENTAS --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
            <form method="GET" action="{{ route('clients.index') }}" class="flex flex-col md:flex-row gap-4">
                {{-- Busca --}}
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Buscar por nome, email ou empresa..." 
                           class="pl-10 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Filtros --}}
                <div class="flex gap-4">
                    <select name="company" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                        <option value="">Todas Empresas</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa }}" {{ request('company') == $empresa ? 'selected' : '' }}>{{ $empresa }}</option>
                        @endforeach
                    </select>
                    
                    <button type="submit" class="bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 text-white px-6 py-2 rounded-lg transition font-medium">
                        Filtrar
                    </button>
                    
                    @if(request()->hasAny(['search', 'company']))
                        <a href="{{ route('clients.index') }}" class="flex items-center justify-center px-4 py-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 transition" title="Limpar Filtros">
                            ✕
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- INFO BAR: TOTAL E FILTROS --}}
        <div class="flex justify-between items-center mb-4 px-1">
            <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                @if(request()->hasAny(['search', 'company']) && $clients->total() < \App\Models\Client::count())
                    Exibindo <span class="text-gray-900 dark:text-white font-bold">{{ $clients->total() }}</span> resultados (de {{ \App\Models\Client::count() }})
                @else
                    Total de Clientes: <span class="text-gray-900 dark:text-white font-bold">{{ $clients->total() }}</span>
                @endif
            </div>
            
            @if(request()->hasAny(['search', 'company']))
                <a href="{{ route('clients.index') }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                    Limpar Filtros
                </a>
            @endif
        </div>

        {{-- TABELA DE CLIENTES (COM SCROLL) --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col h-[calc(100vh-280px)]">
            @if($clients->count() > 0)
                {{-- Cabeçalho Fixo --}}
                <div class="overflow-x-auto overflow-y-hidden border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/4">Cliente</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/4">Contatos</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/6">Localização</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/6">Última Interação</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/6">Ações</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                {{-- Corpo Scrollável --}}
                <div class="overflow-y-auto flex-1">
                    <table class="w-full text-left whitespace-nowrap">
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($clients as $client)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition duration-150 group">
                                    {{-- COLUNA 1: Avatar e Nome --}}
                                    <td class="px-6 py-4 w-1/4">
                                        <div class="flex items-center">
                                            <x-avatar :name="$client->name" class="h-10 w-10 shrink-0" />
                                            <div class="ml-4 truncate">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-blue-600 transition truncate">
                                                    {{ $client->name }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center mt-0.5 truncate">
                                                    {{ $client->company_name ?? 'Particular' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- COLUNA 2: Contatos --}}
                                    <td class="px-6 py-4 w-1/4">
                                        <div class="flex flex-col gap-1">
                                            <a href="mailto:{{ $client->email }}" class="flex items-center text-sm text-gray-600 dark:text-gray-300 hover:text-blue-600 transition truncate" title="{{ $client->email }}">
                                                <svg class="w-4 h-4 mr-2 opacity-70 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                {{ Str::limit($client->email, 20) }}
                                            </a>
                                            <a href="https://wa.me/55{{ preg_replace('/\D/', '', $client->phone) }}" target="_blank" class="flex items-center text-sm text-gray-600 dark:text-gray-300 hover:text-green-600 transition truncate">
                                                <svg class="w-4 h-4 mr-2 opacity-70 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                                {{ $client->phone }}
                                            </a>
                                        </div>
                                    </td>

                                    {{-- COLUNA 3: Localização --}}
                                    <td class="px-6 py-4 w-1/6">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                            {{ $client->city ?? 'N/A' }} / {{ $client->state ?? 'UF' }}
                                        </span>
                                    </td>

                                    {{-- COLUNA 4: Última Interação --}}
                                    <td class="px-6 py-4 w-1/6">
                                        @php
                                            $lastNote = $client->latestNote;
                                            $days = $lastNote ? floor($lastNote->created_at->diffInDays(now())) : 999;
                                            $statusColor = $days < 7 ? 'text-green-600 bg-green-50' : ($days < 30 ? 'text-yellow-600 bg-yellow-50' : 'text-red-500 bg-red-50');
                                            $statusText = $lastNote ? $lastNote->created_at->format('d/m/Y') : 'Nunca';
                                        @endphp
                                        <div class="flex items-center">
                                            <span class="px-2 py-1 rounded text-xs font-bold {{ $statusColor }} dark:bg-transparent">
                                                {{ $statusText }}
                                            </span>
                                            @if($lastNote)
                                                <span class="text-xs text-gray-400 ml-2">({{ $days }}d atrás)</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- COLUNA 5: Ações --}}
                                    <td class="px-6 py-4 w-1/6 text-right text-sm font-medium">
                                        <div class="flex justify-end items-center gap-3">
                                            <a href="{{ route('clients.show', $client->id) }}" class="text-gray-400 hover:text-blue-600 transition transform hover:scale-110" title="Ver Detalhes">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <a href="{{ route('clients.edit', $client->id) }}" class="text-gray-400 hover:text-amber-500 transition transform hover:scale-110" title="Editar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="inline" onsubmit="return confirm('Mover este cliente para a Lixeira?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-500 transition transform hover:scale-110 pt-1" title="Excluir">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINAÇÃO (FIXA NA BASE) --}}
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700">
                    {{ $clients->links() }}
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-16">
                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-full mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Nenhum cliente encontrado</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Tente mudar os filtros ou cadastre um novo cliente.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection