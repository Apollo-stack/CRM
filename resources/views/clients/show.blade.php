@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    {{-- CABEÇALHO --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white mb-1">Cliente: {{ $client->name }}</h1>
            <p class="text-gray-400 text-sm">Cadastrado em {{ $client->created_at->format('d/m/Y') }}</p>
        </div>
        <div class="flex gap-3">
             <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $client->phone) }}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                WhatsApp
            </a>
            <a href="{{ route('leads.create', ['client_id' => $client->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Novo Negócio
            </a>
            <a href="{{ route('clients.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition">Voltar</a>
        </div>
    </div>

    {{-- DESTAQUE LTV --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-green-900 to-green-800 rounded-lg p-6 border border-green-700 shadow-lg relative overflow-hidden">
            <div class="absolute right-0 top-0 opacity-10 transform translate-x-2 -translate-y-2">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"/></svg>
            </div>
            <p class="text-green-200 text-sm font-bold uppercase tracking-wider mb-1">Lifetime Value (Total Gasto)</p>
            <h2 class="text-3xl font-black text-white">R$ {{ number_format($salesHistory->sum('value'), 2, ',', '.') }}</h2>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 hover:border-blue-500 transition cursor-default">
            <p class="text-gray-400 text-sm font-bold uppercase tracking-wider mb-1">Última Compra</p>
            <h2 class="text-2xl font-bold text-white">
                {{ $salesHistory->first() ? $salesHistory->first()->updated_at->format('d/m/Y') : '--/--/----' }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                {{ $salesHistory->first() ? $salesHistory->first()->title : 'Nenhuma compra registrada' }}
            </p>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 hover:border-yellow-500 transition cursor-default">
            <p class="text-gray-400 text-sm font-bold uppercase tracking-wider mb-1">Status Atual</p>
             @if($openLeads->count() > 0)
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></span>
                    <h2 class="text-2xl font-bold text-white">Ativo</h2>
                </div>
                <p class="text-sm text-gray-500 mt-1">{{ $openLeads->count() }} negócio(s) em aberto</p>
            @else
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-gray-500"></span>
                    <h2 class="text-2xl font-bold text-gray-400">Sem atividade</h2>
                </div>
                <p class="text-sm text-gray-600 mt-1">Nenhum negócio em aberto</p>
            @endif
        </div>
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
            <div>
                <span class="block text-xs font-bold uppercase text-gray-400 mb-1">Responsável</span>
                <div class="flex items-center gap-2">
                    @if($client->user)
                        <div class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center text-xs font-bold text-white border border-gray-600">
                            {{ strtoupper(substr($client->user->name, 0, 2)) }}
                        </div>
                        {{ $client->user->name }}
                    @else
                        <span class="text-gray-500 italic">Não atribuído</span>
                    @endif
                </div>
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