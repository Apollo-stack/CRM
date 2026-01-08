@props(['lead'])

{{-- 1. Mudamos de <a> para <div> para poder ter botões dentro --}}
<div class="block bg-white dark:bg-gray-700 p-4 rounded shadow hover:shadow-md transition duration-200 border-l-4 
    {{ $lead->status == 'won' ? 'border-green-500' : ($lead->status == 'negotiation' ? 'border-blue-500' : 'border-gray-500') }}">
    
    {{-- Título e Link para Ver Detalhes --}}
    <div class="flex justify-between items-start mb-2">
        <a href="{{ route('leads.show', $lead->id) }}" class="font-bold text-gray-800 dark:text-white text-sm hover:underline hover:text-blue-600">
            {{ $lead->title }}
        </a>
        <span class="text-xs text-gray-400">#{{ $lead->id }}</span>
    </div>

    {{-- Nome do Cliente --}}
    <p class="text-xs text-gray-500 dark:text-gray-300 mb-2">{{ $lead->client->name }}</p>

    {{-- Valor e Data --}}
    <div class="flex justify-between items-center mt-3">
        <span class="text-sm font-bold text-gray-700 dark:text-gray-200">
            R$ {{ number_format($lead->value, 2, ',', '.') }}
        </span>
        
        <span class="text-[10px] text-gray-400">
            {{ $lead->created_at->format('d/m') }}
        </span>
    </div>

    {{-- AREA DOS BOTÕES DE AÇÃO (Corrigido) --}}
    <div class="mt-3 flex justify-between items-center border-t pt-3 border-gray-100 dark:border-gray-700">
        
        {{-- Botão: Voltar para Novo (Se não for 'new') --}}
        @if($lead->status !== 'new')
            <form action="{{ route('leads.update', $lead->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" name="status" value="new" class="text-xs text-gray-400 hover:text-gray-600 underline">
                    (Voltar)
                </button>
            </form>
        @else
            <div></div> {{-- Espaço vazio para alinhar --}}
        @endif

        <div class="flex gap-2">
            {{-- Botão: Mover para Negociação --}}
            @if($lead->status === 'new')
                <form action="{{ route('leads.update', $lead->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" name="status" value="negotiation" 
                        class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200 border border-blue-200">
                        Negociar &rarr;
                    </button>
                </form>
            @endif

            {{-- Botão: Marcar como Ganho --}}
            @if($lead->status === 'negotiation')
                <form action="{{ route('leads.update', $lead->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" name="status" value="won" 
                        class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded hover:bg-green-200 border border-green-200">
                        Venda Feita! $
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>