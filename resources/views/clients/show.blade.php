<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Cliente: {{ $client->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Dados Cadastrais --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Dados Cadastrais</h3>
                    <a href="{{ route('clients.edit', $client->id) }}" class="text-blue-500 hover:underline">Editar Dados</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-gray-600 dark:text-gray-300">
                    <div>
                        <span class="block text-xs font-bold uppercase text-gray-400">Email</span>
                        {{ $client->email ?? 'Não informado' }}
                    </div>
                    <div>
                        <span class="block text-xs font-bold uppercase text-gray-400">Telefone</span>
                        {{ $client->phone ?? 'Não informado' }}
                    </div>
                    <div>
                        <span class="block text-xs font-bold uppercase text-gray-400">Empresa</span>
                        {{ $client->company_name ?? 'Particular' }}
                    </div>
                </div>
            </div>
            
            {{-- Endereço --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Endereço</h3>
                <div class="text-gray-600 dark:text-gray-300">
                    @if($client->address)
                        <p class="text-lg">{{ $client->address }}</p>
                        <p>{{ $client->city }} - {{ $client->state }}</p>
                        <p class="text-sm text-gray-500">CEP: {{ $client->cep }}</p>
                    @else
                        <p class="text-gray-500 italic">Endereço não cadastrado.</p>
                    @endif
                </div>
            </div>

            {{-- Histórico de Compras (Vendas Fechadas) --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Histórico de Compras (Ganhos)</h3>
                
                @if($salesHistory->isEmpty())
                    <p class="text-gray-500">Este cliente ainda não comprou nada.</p>
                @else
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead>
                            <tr class="border-b dark:border-gray-700">
                                <th class="pb-2">Data</th>
                                <th class="pb-2">O que comprou</th>
                                <th class="pb-2 text-right">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesHistory as $sale)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="py-2">{{ $sale->updated_at->format('d/m/Y') }}</td>
                                    <td class="py-2 font-medium">{{ $sale->title }}</td>
                                    <td class="py-2 text-right font-bold text-green-600">R$ {{ number_format($sale->value, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- Oportunidades em Aberto --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Negociações em Andamento</h3>
                
                @if($openLeads->isEmpty())
                    <p class="text-gray-500">Nenhuma negociação aberta no momento.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($openLeads as $lead)
                            <x-lead-card :lead="$lead" />
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>