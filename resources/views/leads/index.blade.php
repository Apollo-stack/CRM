<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Pipeline de Vendas') }}
                </h2>
                
                {{-- FILTRO: MEUS vs TODOS --}}
                <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-1 flex text-sm">
                    <a href="{{ route('leads.index') }}" 
                       class="px-3 py-1 rounded-md {{ !request('view') ? 'bg-white dark:bg-gray-600 shadow text-gray-800 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                       Meus
                    </a>
                    <a href="{{ route('leads.index', ['view' => 'all']) }}" 
                       class="px-3 py-1 rounded-md {{ request('view') == 'all' ? 'bg-white dark:bg-gray-600 shadow text-gray-800 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                       Todos
                    </a>
                </div>
            </div>

            <a href="{{ route('leads.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                + Novo Negócio
            </a>
        </div>  
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- GRID: Adicionei 'min-h-screen' para garantir altura --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">

                {{-- COLUNA 1: NOVOS --}}
                <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg min-h-[500px] border border-gray-200 dark:border-gray-700">
                    <h3 class="font-bold text-gray-700 dark:text-gray-300 mb-4 flex justify-between items-center">
                        Novos
                        <span class="bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-xs px-2 py-1 rounded-full">
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
                <div class="bg-blue-50 dark:bg-gray-800/50 p-4 rounded-lg min-h-[500px] border border-blue-100 dark:border-blue-900">
                    <h3 class="font-bold text-blue-800 dark:text-blue-300 mb-4 flex justify-between items-center">
                        Em Negociação
                        <span class="bg-blue-200 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded-full">
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
                <div class="bg-green-50 dark:bg-gray-800/50 p-4 rounded-lg min-h-[500px] border border-green-100 dark:border-green-900">
                    <h3 class="font-bold text-green-800 dark:text-green-300 mb-4 flex justify-between items-center">
                        Ganhos
                        <span class="bg-green-200 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs px-2 py-1 rounded-full">
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
    </div>
</x-app-layout>