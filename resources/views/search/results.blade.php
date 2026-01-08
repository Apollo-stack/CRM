<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Resultados para: "{{ $query }}"
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- RESULTADOS DE CLIENTES --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 mb-4 border-b pb-2">
                    Clientes Encontrados ({{ $clients->count() }})
                </h3>
                
                @if($clients->isEmpty())
                    <p class="text-gray-500">Nenhum cliente encontrado.</p>
                @else
                    <ul class="space-y-3">
                        @foreach($clients as $client)
                            <li class="flex justify-between items-center bg-gray-50 dark:bg-gray-700/50 p-3 rounded">
                                <div>
                                    <div class="font-bold text-gray-800 dark:text-white">{{ $client->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $client->company_name }} - {{ $client->email }}</div>
                                </div>
                                <a href="{{ route('clients.edit', $client->id) }}" class="text-blue-500 hover:underline text-sm">Editar</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- RESULTADOS DE NEGÓCIOS --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 mb-4 border-b pb-2">
                    Negócios Encontrados ({{ $leads->count() }})
                </h3>

                @if($leads->isEmpty())
                    <p class="text-gray-500">Nenhum negócio encontrado.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($leads as $lead)
                            {{-- Reutilizando seu componente de Card --}}
                            <x-lead-card :lead="$lead" />
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>