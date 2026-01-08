<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalhes do Negócio #{{ $lead->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Cartão Principal --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-2xl font-bold mb-2">{{ $lead->title }}</h3>
                                <a href="{{ route('leads.edit', $lead->id) }}" class="text-sm text-blue-500 hover:underline mb-2">(Editar)</a>
                             </div>
                            <p class="text-gray-500">Cliente: <span class="font-bold text-gray-800">{{ $lead->client->name }}</span></p>
                            <p class="text-gray-500">Empresa: {{ $lead->client->company_name ?? 'Não informada' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Valor Estimado</p>
                            <p class="text-3xl font-bold text-green-600">R$ {{ number_format($lead->value, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Área de Ações (Mudança de Status) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Coluna da Esquerda: Status Atual --}}
                <div class="bg-white p-6 shadow-sm rounded-lg">
                    <h4 class="font-bold text-gray-700 mb-4">Status Atual</h4>
                    <span class="px-4 py-2 rounded-full text-white font-bold
                        {{ $lead->status == 'won' ? 'bg-green-500' : ($lead->status == 'lost' ? 'bg-red-500' : 'bg-blue-500') }}">
                        {{ strtoupper($lead->status) }}
                    </span>
                </div>

                {{-- Coluna do Meio/Direita: Botões de Ação --}}
                <div class="bg-white p-6 shadow-sm rounded-lg col-span-2">
                    <h4 class="font-bold text-gray-700 mb-4">Mover Estágio</h4>
                    <div class="flex gap-4">
                        {{-- Botão para voltar pra negociação --}}
                        <form action="{{ route('leads.update', $lead->id) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="negotiation">
                            <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
                                Em Negociação
                            </button>
                        </form>

                        {{-- Botão Ganho --}}
                        <form action="{{ route('leads.update', $lead->id) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="won">
                            <button type="submit" class="bg-green-100 hover:bg-green-200 text-green-700 border border-green-300 px-4 py-2 rounded">
                                Marcar como Ganho
                            </button>
                        </form>

                        {{-- Botão Perdido --}}
                        <form action="{{ route('leads.update', $lead->id) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="lost">
                            <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 border border-red-300 px-4 py-2 rounded">
                                Marcar como Perdido
                            </button>
                        </form>
                    </div>
                </div>

            </div>
            
            <div class="mt-6">
                 <a href="{{ route('leads.index') }}" class="text-gray-500 hover:underline">← Voltar para lista</a>
            </div>

        </div>
{{-- ÁREA DE HISTÓRICO / TIMELINE --}}
        <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">Histórico do Negócio</h3>

            <form action="{{ route('leads.notes.store', $lead->id) }}" method="POST" class="mb-8">
            @csrf

            <div class="flex gap-2 mb-2">
                {{-- Seletor de Tipo com Ícones (Truque visual com Radio Buttons) --}}
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="call" class="peer sr-only">
                    <span class="px-3 py-1 rounded-full border text-sm text-gray-500 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500">
                        📞 Ligação
                    </span>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="whatsapp" class="peer sr-only">
                    <span class="px-3 py-1 rounded-full border text-sm text-gray-500 peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500">
                        💬 WhatsApp
                    </span>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="email" class="peer sr-only">
                    <span class="px-3 py-1 rounded-full border text-sm text-gray-500 peer-checked:bg-yellow-500 peer-checked:text-white peer-checked:border-yellow-500">
                        📧 Email
                    </span>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="meeting" class="peer sr-only">
                    <span class="px-3 py-1 rounded-full border text-sm text-gray-500 peer-checked:bg-purple-500 peer-checked:text-white peer-checked:border-purple-500">
                        🤝 Reunião
                    </span>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="note" class="peer sr-only" checked>
                    <span class="px-3 py-1 rounded-full border text-sm text-gray-500 peer-checked:bg-gray-500 peer-checked:text-white peer-checked:border-gray-500">
                        📝 Nota
                    </span>
                </label>
            </div>

            <textarea name="content" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Detalhes da interação..."></textarea>

                <div class="mt-2 text-right">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Registrar
                    </button>
                </div>
            </form>

            {{-- Lista de Notas --}}
            <div class="space-y-6">
                 @foreach($lead->notes as $note)
                    <div class="flex gap-4">
                        {{-- Ícone Dinâmico baseado no Tipo --}}
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-white shadow-sm
                            @switch($note->type)
                                @case('call') bg-blue-500 @break
                                @case('whatsapp') bg-green-500 @break
                                @case('email') bg-yellow-500 @break
                                @case('meeting') bg-purple-500 @break
                                @default bg-gray-500
                            @endswitch
                        ">
                            @switch($note->type)
                                @case('call') 📞 @break
                                @case('whatsapp') 💬 @break
                                @case('email') 📧 @break
                                @case('meeting') 🤝 @break
                                @default 📝
                            @endswitch
                        </div>
                            
                        <div class="flex-grow bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-gray-800 dark:text-gray-200">
                                    {{ $note->user->name }}
                                    <span class="text-xs font-normal text-gray-500 ml-2">via {{ ucfirst($note->type) }}</span>
                                </span>
                                <span class="text-xs text-gray-500">{{ $note->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $note->content }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
{{-- FIM DA TIMELINE --}}
    </div>
</x-app-layout>