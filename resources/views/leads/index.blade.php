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

        <a href="{{ route('leads.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
            + Novo Negócio
        </a>
    </div>

    {{-- GRID DO PIPELINE --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- COLUNA 1: NOVOS --}}
        <div class="bg-gray-800 p-4 rounded-lg min-h-[500px]">
            <h3 class="font-bold text-gray-300 mb-4 flex justify-between items-center">
                Novos
                <span class="bg-gray-600 text-gray-200 text-xs px-2 py-1 rounded-full">
                    {{ $leads->where('status', 'new')->count() }}
                </span>
            </h3>
            
            <div class="space-y-3">
                @foreach($leads->where('status', 'new') as $lead)
                    <x-lead-card :lead="$lead" />
                @endforeach
            </div>
        </div>

        {{-- COLUNA 2: EM NEGOCIAÇÃO --}}
        <div class="bg-gray-800 p-4 rounded-lg min-h-[500px]">
            <h3 class="font-bold text-blue-300 mb-4 flex justify-between items-center">
                Em Negociação
                <span class="bg-blue-900 text-blue-200 text-xs px-2 py-1 rounded-full">
                    {{ $leads->where('status', 'negotiation')->count() }}
                </span>
            </h3>

            <div class="space-y-3">
                @foreach($leads->where('status', 'negotiation') as $lead)
                    <x-lead-card :lead="$lead" />
                @endforeach
            </div>
        </div>

        {{-- COLUNA 3: GANHOS --}}
        <div class="bg-gray-800 p-4 rounded-lg min-h-[500px]">
            <h3 class="font-bold text-green-300 mb-4 flex justify-between items-center">
                Ganhos
                <span class="bg-green-900 text-green-200 text-xs px-2 py-1 rounded-full">
                    {{ $leads->where('status', 'won')->count() }}
                </span>
            </h3>

            <div class="space-y-3">
                @foreach($leads->where('status', 'won') as $lead)
                    <x-lead-card :lead="$lead" />
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection