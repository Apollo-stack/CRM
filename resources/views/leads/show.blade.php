@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    {{-- CABEÇALHO --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-white">{{ $lead->title }}</h1>
        <a href="{{ route('leads.index') }}" class="text-gray-400 hover:text-white">
            ← Voltar para lista
        </a>
    </div>

    {{-- INFORMAÇÕES DO NEGÓCIO --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        
        {{-- CARD: Informações Básicas --}}
        <div class="bg-gray-800 rounded-lg p-6">
            <h3 class="text-xl font-bold text-white mb-4">Informações Básicas</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-400 text-sm">Cliente:</span>
                    <p class="text-white font-medium">{{ $lead->client ? $lead->client->name : 'Cliente não encontrado' }}</p>
                    @if($lead->client)
                        <p class="text-gray-400 text-sm">{{ $lead->client->company_name ?? 'Particular' }}</p>
                    @endif
                </div>
                <div>
                    <span class="text-gray-400 text-sm">Valor Estimado:</span>
                    <p class="text-2xl font-bold text-green-400">R$ {{ number_format($lead->value, 2, ',', '.') }}</p>
                </div>
                <div>
                    <span class="text-gray-400 text-sm">Data de Criação:</span>
                    <p class="text-white">{{ $lead->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- CARD: Status e Ações --}}
        <div class="bg-gray-800 rounded-lg p-6">
            <h3 class="text-xl font-bold text-white mb-4">Status Atual</h3>
            
            <div class="mb-6">
                @if($lead->status === \App\LeadStatus::NEW)
                    <span class="inline-block bg-gray-600 text-white px-4 py-2 rounded-lg text-lg font-bold">NOVO</span>
                @elseif($lead->status === \App\LeadStatus::NEGOTIATION)
                    <span class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-lg font-bold">EM NEGOCIAÇÃO</span>
                @elseif($lead->status === \App\LeadStatus::WON)
                    <span class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg text-lg font-bold">GANHO 🎉</span>
                @elseif($lead->status === \App\LeadStatus::LOST)
                    <span class="inline-block bg-red-600 text-white px-4 py-2 rounded-lg text-lg font-bold">PERDIDO</span>
                @endif
            </div>

            <h4 class="text-white font-bold mb-3">Mover Estágio</h4>
            <div class="space-y-2">
                
                {{-- Botão: Em Negociação --}}
                @if($lead->status !== \App\LeadStatus::NEGOTIATION && $lead->status !== \App\LeadStatus::WON && $lead->status !== \App\LeadStatus::LOST)
                    <form action="{{ route('leads.update', $lead->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        {{-- CORREÇÃO: Input Hidden adicionado --}}
                        <input type="hidden" name="status" value="negotiation">
                        <button type="submit" 
                                class="w-full bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 border border-blue-200 font-medium">
                            Em Negociação
                        </button>
                    </form>
                @endif

                @if($lead->status === \App\LeadStatus::NEGOTIATION)
                    {{-- Botão: Ganho --}}
                    <form action="{{ route('leads.update', $lead->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        {{-- CORREÇÃO: Input Hidden adicionado --}}
                        <input type="hidden" name="status" value="won">
                        <button type="submit" 
                                class="w-full bg-green-100 text-green-700 px-4 py-2 rounded-lg hover:bg-green-200 border border-green-200 font-medium">
                            Marcar como Ganho
                        </button>
                    </form>
                    
                    {{-- Botão: Perdido --}}
                    <form action="{{ route('leads.update', $lead->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        {{-- CORREÇÃO: Input Hidden adicionado --}}
                        <input type="hidden" name="status" value="lost">
                        <button type="submit" 
                                class="w-full bg-red-100 text-red-700 px-4 py-2 rounded-lg hover:bg-red-200 border border-red-200 font-medium">
                            Marcar como Perdido
                        </button>
                    </form>
                @endif
                
                {{-- Botão: Reabrir (Voltar para Novo) --}}
                @if($lead->status === \App\LeadStatus::LOST || $lead->status === \App\LeadStatus::WON)
                     <form action="{{ route('leads.update', $lead->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="new">
                        <button type="submit" 
                                class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-500 border border-gray-500 font-medium">
                            Reabrir Negócio
                        </button>
                    </form>
                @endif

                <a href="{{ route('leads.edit', $lead->id) }}" 
                   class="block w-full bg-gray-700 text-white text-center px-4 py-2 rounded-lg hover:bg-gray-600 font-medium">
                    Editar Informações
                </a>
            </div>
        </div>
    </div>

    {{-- HISTÓRICO DO NEGÓCIO --}}
    <div class="bg-gray-800 rounded-lg p-6">
        <h3 class="text-xl font-bold text-white mb-4">Histórico do Negócio</h3>

        {{-- FORMULÁRIO PARA ADICIONAR INTERAÇÃO --}}
        <form action="{{ route('leads.notes.store', $lead->id) }}" method="POST" class="mb-6">
            @csrf
            <div class="flex gap-2 mb-3">
                <button type="button" onclick="setType('call')" 
                        class="interaction-btn px-3 py-1 rounded-lg text-sm bg-gray-700 text-white hover:bg-red-600">
                    📞 Ligação
                </button>
                <button type="button" onclick="setType('whatsapp')" 
                        class="interaction-btn px-3 py-1 rounded-lg text-sm bg-gray-700 text-white hover:bg-green-600">
                    💬 WhatsApp
                </button>
                <button type="button" onclick="setType('email')" 
                        class="interaction-btn px-3 py-1 rounded-lg text-sm bg-gray-700 text-white hover:bg-blue-600">
                    📧 Email
                </button>
                <button type="button" onclick="setType('meeting')" 
                        class="interaction-btn px-3 py-1 rounded-lg text-sm bg-gray-700 text-white hover:bg-yellow-600">
                    🤝 Reunião
                </button>
                <button type="button" onclick="setType('note')" 
                        class="interaction-btn px-3 py-1 rounded-lg text-sm bg-gray-700 text-white hover:bg-purple-600">
                    📝 Nota
                </button>
            </div>

            <input type="hidden" name="type" id="interaction-type" value="note">
            
            <textarea name="content" 
                      id="interaction-content"
                      placeholder="Detalhes da interação..." 
                      rows="3"
                      required
                      class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none mb-3"></textarea>
            
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                Registrar
            </button>
        </form>

        {{-- LISTA DE INTERAÇÕES --}}
        <div class="space-y-3">
            @forelse($lead->notes()->latest()->get() as $note)
                <div class="bg-gray-700 rounded-lg p-4 border-l-4 
                    {{ $note->type == 'call' ? 'border-red-500' : '' }}
                    {{ $note->type == 'whatsapp' ? 'border-green-500' : '' }}
                    {{ $note->type == 'email' ? 'border-blue-500' : '' }}
                    {{ $note->type == 'meeting' ? 'border-yellow-500' : '' }}
                    {{ $note->type == 'note' ? 'border-purple-500' : '' }}">
                    
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-2">
                            @if($note->type == 'call') <span>📞</span>
                            @elseif($note->type == 'whatsapp') <span>💬</span>
                            @elseif($note->type == 'email') <span>📧</span>
                            @elseif($note->type == 'meeting') <span>🤝</span>
                            @else <span>📝</span>
                            @endif
                            
                            <span class="text-gray-400 text-sm">
                                {{ $note->user->name ?? 'Usuário' }} • 
                                via {{ ucfirst($note->type) }}
                            </span>
                        </div>
                        <span class="text-gray-500 text-xs">
                            {{ $note->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    
                    <p class="text-white">{{ $note->content }}</p>
                </div>
            @empty
                <p class="text-gray-400 text-center py-8">Nenhuma interação registrada ainda.</p>
            @endforelse
        </div>
    </div>

</div>

<script>
function setType(type) {
    document.getElementById('interaction-type').value = type;
    
    // Atualiza o placeholder
    const content = document.getElementById('interaction-content');
    const placeholders = {
        'call': 'Ex: Cliente interessado em orçamento, pediu prazo de entrega...',
        'whatsapp': 'Ex: Enviou mensagem confirmando interesse...',
        'email': 'Ex: Enviado proposta comercial...',
        'meeting': 'Ex: Reunião realizada, cliente pediu 10% de desconto...',
        'note': 'Ex: Cliente está analisando a proposta...'
    };
    content.placeholder = placeholders[type] || 'Detalhes da interação...';
    
    // Visual feedback
    document.querySelectorAll('.interaction-btn').forEach(btn => {
        btn.classList.remove('ring-2', 'ring-white');
    });
    event.target.classList.add('ring-2', 'ring-white');
}
</script>
@endsection