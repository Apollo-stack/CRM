@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    {{-- CABEÇALHO --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-white">Cliente: {{ $client->name }}</h1>
        <a href="{{ route('clients.index') }}" class="text-gray-400 hover:text-white">
            ← Voltar para lista
        </a>
    </div>

    {{-- DADOS CADASTRAIS --}}
    <div class="bg-gray-800 rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-white">Dados Cadastrais</h3>
            <a href="{{ route('clients.edit', $client->id) }}" class="text-blue-400 hover:underline">Editar Dados</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-gray-300">
            <div>
                <span class="block text-xs font-bold uppercase text-gray-400 mb-1">Email</span>
                <a href="mailto:{{ $client->email }}" class="text-blue-400 hover:underline">
                    {{ $client->email ?? 'Não informado' }}
                </a>
            </div>
            <div>
                <span class="block text-xs font-bold uppercase text-gray-400 mb-1">Telefone</span>
                {{ $client->phone ?? 'Não informado' }}
            </div>
            <div>
                <span class="block text-xs font-bold uppercase text-gray-400 mb-1">Empresa</span>
                {{ $client->company_name ?? 'Particular' }}
            </div>
        </div>
    </div>
    
    {{-- ENDEREÇO --}}
    <div class="bg-gray-800 rounded-lg p-6 mb-6">
        <h3 class="text-xl font-bold text-white mb-4">Endereço</h3>
        <div class="text-gray-300">
            @if($client->address)
                <p class="text-lg">{{ $client->address }}</p>
                <p>{{ $client->city }} - {{ $client->state }}</p>
                <p class="text-sm text-gray-500">CEP: {{ $client->cep }}</p>
            @else
                <p class="text-gray-500 italic">Endereço não cadastrado.</p>
            @endif
        </div>
    </div>

    {{-- HISTÓRICO DE COMPRAS --}}
    <div class="bg-gray-800 rounded-lg p-6 mb-6 border-l-4 border-green-500">
        <h3 class="text-xl font-bold text-white mb-4">Histórico de Compras (Ganhos)</h3>
        
        @if($salesHistory->isEmpty())
            <p class="text-gray-400">Este cliente ainda não comprou nada.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="border-b border-gray-700">
                        <tr>
                            <th class="pb-3">Data</th>
                            <th class="pb-3">O que comprou</th>
                            <th class="pb-3 text-right">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesHistory as $sale)
                            <tr class="border-b border-gray-700">
                                <td class="py-3">{{ $sale->updated_at->format('d/m/Y') }}</td>
                                <td class="py-3 font-medium">{{ $sale->title }}</td>
                                <td class="py-3 text-right font-bold text-green-400">
                                    R$ {{ number_format($sale->value, 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t-2 border-gray-600">
                        <tr>
                            <td colspan="2" class="pt-3 font-bold text-white">TOTAL</td>
                            <td class="pt-3 text-right font-bold text-green-400 text-lg">
                                R$ {{ number_format($salesHistory->sum('value'), 2, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

    {{-- OPORTUNIDADES EM ABERTO --}}
    <div class="bg-gray-800 rounded-lg p-6 border-l-4 border-blue-500">
        <h3 class="text-xl font-bold text-white mb-4">Negociações em Andamento</h3>
        
        @if($openLeads->isEmpty())
            <p class="text-gray-400">Nenhuma negociação aberta no momento.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($openLeads as $lead)
                    <div class="bg-gray-700 rounded-lg p-4 border-l-4 
                        {{ $lead->status == 'new' ? 'border-gray-500' : 'border-blue-500' }}">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-bold text-white">{{ $lead->title }}</h4>
                            <span class="text-xs px-2 py-1 rounded
                                {{ $lead->status == 'new' ? 'bg-gray-600 text-white' : '' }}
                                {{ $lead->status == 'negotiation' ? 'bg-blue-600 text-white' : '' }}">
                                {{ $lead->status == 'new' ? 'Novo' : 'Em Negociação' }}
                            </span>
                        </div>
                        <p class="text-green-400 font-bold text-lg mb-2">
                            R$ {{ number_format($lead->value, 2, ',', '.') }}
                        </p>
                        <a href="{{ route('leads.show', $lead->id) }}" 
                           class="text-blue-400 hover:underline text-sm">
                            Ver detalhes →
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection