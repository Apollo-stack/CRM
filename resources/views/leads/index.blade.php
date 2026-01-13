@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    {{-- CABEÇALHO --}}
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <h1 class="text-3xl font-bold text-white">Pipeline de Vendas</h1>
            
            {{-- FILTRO: MEUS vs TODOS --}}
            <div class="bg-gray-700 rounded-lg p-1 flex text-sm">
                <a href="{{ route('leads.index') }}" 
                   class="px-3 py-1 rounded-md {{ !request('view') ? 'bg-gray-600 text-white' : 'text-gray-400 hover:text-white' }}">
                   Meus
                </a>
                <a href="{{ route('leads.index', ['view' => 'all']) }}" 
                   class="px-3 py-1 rounded-md {{ request('view') == 'all' ? 'bg-gray-600 text-white' : 'text-gray-400 hover:text-white' }}">
                   Todos
                </a>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('leads.trash') }}" 
                class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-3 rounded-lg font-medium transition flex items-center" title="Ver Lixeira">
                🗑️
            </a>
            <a href="{{ route('leads.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                + Novo Negócio
            </a>
        </div>
    </div>

    {{-- GRID DO PIPELINE (COM SCROLL INDEPENDENTE) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 h-[calc(100vh-180px)] min-h-[500px]">

        {{-- COLUNA 1: NOVOS --}}
        <div class="bg-gray-800 p-4 rounded-lg flex flex-col h-full shadow-lg border border-gray-700 overflow-hidden">
            <h3 class="font-bold text-gray-300 mb-4 flex justify-between items-center shrink-0">
                Novos
                <span class="bg-gray-700 text-gray-200 text-xs px-2 py-1 rounded-full border border-gray-600">
                    {{ $leads->where('status', \App\LeadStatus::NEW)->count() }}
                </span>
            </h3>
            
            <div class="space-y-3 flex-1 overflow-y-auto pr-2 custom-scrollbar pb-2" id="kanban-new" data-status="new">
                @foreach($leads->where('status', \App\LeadStatus::NEW) as $lead)
                    <div data-id="{{ $lead->id }}">
                        <x-lead-card :lead="$lead" />
                    </div>
                @endforeach
            </div>
        </div>

        {{-- COLUNA 2: EM NEGOCIAÇÃO --}}
        <div class="bg-gray-800 p-4 rounded-lg flex flex-col h-full shadow-lg border border-gray-700 overflow-hidden">
            <h3 class="font-bold text-blue-300 mb-4 flex justify-between items-center shrink-0">
                Em Negociação
                <span class="bg-blue-900/50 text-blue-200 text-xs px-2 py-1 rounded-full border border-blue-800">
                    {{ $leads->where('status', \App\LeadStatus::NEGOTIATION)->count() }}
                </span>
            </h3>

            <div class="space-y-3 flex-1 overflow-y-auto pr-2 custom-scrollbar pb-2" id="kanban-negotiation" data-status="negotiation">
                @foreach($leads->where('status', \App\LeadStatus::NEGOTIATION) as $lead)
                    <div data-id="{{ $lead->id }}">
                        <x-lead-card :lead="$lead" />
                    </div>
                @endforeach
            </div>
        </div>

        {{-- COLUNA 3: GANHOS --}}
        <div class="bg-gray-800 p-4 rounded-lg flex flex-col h-full shadow-lg border border-gray-700 overflow-hidden">
            <h3 class="font-bold text-green-300 mb-4 flex justify-between items-center shrink-0">
                Ganhos
                <span class="bg-green-900/50 text-green-200 text-xs px-2 py-1 rounded-full border border-green-800">
                    {{ $leads->where('status', \App\LeadStatus::WON)->count() }}
                </span>
            </h3>

            <div class="space-y-3 flex-1 overflow-y-auto pr-2 custom-scrollbar pb-2" id="kanban-won" data-status="won">
                @foreach($leads->where('status', \App\LeadStatus::WON) as $lead)
                    <div data-id="{{ $lead->id }}">
                        <x-lead-card :lead="$lead" />
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>

<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.25);
    }
</style>
@endsection