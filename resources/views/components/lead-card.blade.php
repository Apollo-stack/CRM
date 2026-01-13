@props(['lead'])

<div class="block bg-white dark:bg-gray-700 p-4 rounded shadow hover:shadow-md transition duration-200 border-l-4 
    {{ $lead->status === \App\LeadStatus::WON ? 'border-green-500' : ($lead->status === \App\LeadStatus::NEGOTIATION ? 'border-blue-500' : 'border-gray-500') }}">
    
    {{-- Título e Link --}}
    <div class="flex justify-between items-start mb-2">
        <div class="flex flex-col">
            <a href="{{ route('leads.show', $lead->id) }}" class="font-bold text-gray-800 dark:text-white text-sm hover:underline hover:text-blue-600 block mb-1">
                {{ $lead->title }}
            </a>
            <span class="text-xs text-gray-400">#{{ $lead->id }}</span>
        </div>
        
        <div class="flex items-center gap-2">
            @if($lead->user)
                <div title="Responsável: {{ $lead->user->name }}">
                    <x-avatar :name="$lead->user->name" class="w-6 h-6 text-[10px]" />
                </div>
            @endif
            
            <form action="{{ route('leads.destroy', $lead->id) }}" method="POST" onsubmit="return confirm('Mover para lixeira?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-gray-400 hover:text-red-500 transition pt-0.5" title="Excluir">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </form>
        </div>
    </div>

    {{-- Cliente --}}
    <p class="text-xs text-gray-500 dark:text-gray-300 mb-2">
        {{ $lead->client ? $lead->client->name : 'Cliente não encontrado' }}
    </p>
    
    {{-- Valor e Data --}}
    <div class="flex justify-between items-center mt-3">
        <span class="text-sm font-bold text-gray-700 dark:text-gray-200">
            R$ {{ number_format($lead->value, 2, ',', '.') }}
        </span>
        <span class="text-[10px] text-gray-400">
            {{ $lead->created_at->format('d/m') }}
        </span>
    </div>

    {{-- AREA DOS BOTÕES DE AÇÃO --}}
    <div class="mt-3 flex justify-between items-center border-t pt-3 border-gray-100 dark:border-gray-700">
        
        {{-- Botão: Voltar para Novo --}}
        @if($lead->status !== \App\LeadStatus::NEW)
            <form action="{{ route('leads.update', $lead->id) }}" method="POST">
                @csrf
                @method('PUT')
                {{-- CORREÇÃO: Input Hidden para garantir o envio --}}
                <input type="hidden" name="status" value="new">
                <button type="submit" class="text-xs text-gray-400 hover:text-gray-600 underline">
                    (Voltar)
                </button>
            </form>
        @else
            <div></div>
        @endif

        <div class="flex gap-2">
            {{-- Botão: Mover para Negociação --}}
            @if($lead->status === \App\LeadStatus::NEW)
                <form action="{{ route('leads.update', $lead->id) }}" 
                      method="POST"
                      onsubmit="addButtonLoading(this.querySelector('button'), 'Movendo...')">
                    @csrf
                    @method('PUT')
                    {{-- CORREÇÃO AQUI --}}
                    <input type="hidden" name="status" value="negotiation">
                    <button type="submit" 
                            class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200 border border-blue-200">
                        Negociar →
                    </button>
                </form>
            @endif

            {{-- Botão: Marcar como Ganho --}}
            @if($lead->status === \App\LeadStatus::NEGOTIATION)
                <form action="{{ route('leads.update', $lead->id) }}" 
                      method="POST"
                      onsubmit="addButtonLoading(this.querySelector('button'), 'Salvando...')">
                    @csrf
                    @method('PUT')
                    {{-- CORREÇÃO AQUI --}}
                    <input type="hidden" name="status" value="won">
                    <button type="submit" 
                            class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded hover:bg-green-200 border border-green-200">
                        Venda Feita! $
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>