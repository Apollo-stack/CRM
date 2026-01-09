@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    {{-- CABEÇALHO COM ESTATÍSTICAS --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Resultados da Busca</h1>
        <div class="flex items-center gap-4 text-gray-400">
            <p>Você buscou por: <span class="text-white font-medium">"{{ $query }}"</span></p>
            <span>•</span>
            <p><span class="text-white font-bold">{{ $totalResults }}</span> resultados encontrados</p>
            @if($leads->count() > 0)
                <span>•</span>
                <p>Valor total: <span class="text-green-400 font-bold">R$ {{ number_format($totalValue, 2, ',', '.') }}</span></p>
            @endif
        </div>
    </div>

    {{-- SE NÃO ENCONTROU NADA --}}
    @if($totalResults == 0)
        <div class="bg-gray-800 rounded-lg p-12 text-center">
            <p class="text-gray-400 text-lg mb-4">Nenhum resultado encontrado para "{{ $query }}"</p>
            <p class="text-gray-500 text-sm mb-6">Tente buscar por:</p>
            <ul class="text-gray-500 text-sm space-y-1 mb-6">
                <li>• Nome do cliente ou empresa</li>
                <li>• Email ou telefone</li>
                <li>• Título do negócio</li>
                <li>• Valor (ex: "1000")</li>
                <li>• Status (ex: "ganho", "negociação")</li>
            </ul>
            <a href="{{ route('dashboard') }}" 
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                Voltar ao Dashboard
            </a>
        </div>
    @endif

    {{-- CLIENTES ENCONTRADOS --}}
    @if($clients->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-white mb-4 flex items-center gap-2">
                <span>👤 Clientes</span>
                <span class="bg-blue-600 text-white text-sm px-3 py-1 rounded-full">{{ $clients->count() }}</span>
            </h2>
            
            <div class="bg-gray-800 rounded-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Nome</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Empresa</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Contato</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-300">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($clients as $client)
                            <tr class="hover:bg-gray-700 transition">
                                <td class="px-6 py-4 text-white font-medium">{{ $client->name }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ $client->company_name ?? 'Particular' }}</td>
                                <td class="px-6 py-4">
                                    <p class="text-gray-400 text-sm">{{ $client->email }}</p>
                                    <p class="text-gray-500 text-xs">{{ $client->phone }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('clients.show', $client->id) }}" 
                                       class="text-blue-400 hover:underline font-medium">
                                        Ver Detalhes →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- NEGÓCIOS ENCONTRADOS --}}
    @if($leads->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-white mb-4 flex items-center gap-2">
                <span>💼 Negócios</span>
                <span class="bg-green-600 text-white text-sm px-3 py-1 rounded-full">{{ $leads->count() }}</span>
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($leads as $lead)
                    <div class="bg-gray-800 rounded-lg p-5 border-l-4 hover:shadow-lg transition
                        {{ $lead->status == 'new' ? 'border-gray-500' : '' }}
                        {{ $lead->status == 'negotiation' ? 'border-blue-500' : '' }}
                        {{ $lead->status == 'won' ? 'border-green-500' : '' }}
                        {{ $lead->status == 'lost' ? 'border-red-500' : '' }}">
                        
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-bold text-white text-lg">{{ $lead->title }}</h3>
                            <span class="text-xs px-2 py-1 rounded font-medium
                                {{ $lead->status == 'new' ? 'bg-gray-600 text-white' : '' }}
                                {{ $lead->status == 'negotiation' ? 'bg-blue-600 text-white' : '' }}
                                {{ $lead->status == 'won' ? 'bg-green-600 text-white' : '' }}
                                {{ $lead->status == 'lost' ? 'bg-red-600 text-white' : '' }}">
                                @if($lead->status == 'new') Novo
                                @elseif($lead->status == 'negotiation') Negociação
                                @elseif($lead->status == 'won') Ganho
                                @else Perdido
                                @endif
                            </span>
                        </div>
                        
                        <p class="text-gray-400 text-sm mb-3">
                            📞 {{ $lead->client ? $lead->client->name : 'Cliente não encontrado' }}
                        </p>
                        
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-green-400 font-bold text-xl">
                                R$ {{ number_format($lead->value, 2, ',', '.') }}
                            </p>
                            <span class="text-gray-500 text-xs">
                                {{ $lead->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                        
                        <a href="{{ route('leads.show', $lead->id) }}" 
                           class="block w-full text-center bg-gray-700 hover:bg-gray-600 text-white py-2 rounded-lg font-medium transition">
                            Ver Detalhes
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- VOLTAR --}}
    @if($totalResults > 0)
        <div class="mt-8 text-center">
            <a href="{{ route('dashboard') }}" 
               class="inline-block bg-gray-700 hover:bg-gray-600 text-white px-8 py-3 rounded-lg font-medium transition">
                ← Voltar ao Dashboard
            </a>
        </div>
    @endif

</div>
@endsection