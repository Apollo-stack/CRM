@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-3xl">
    
    {{-- CABEÇALHO --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-white">Editar Negócio</h1>
        <a href="{{ route('leads.show', $lead->id) }}" class="text-gray-400 hover:text-white">
            ← Voltar
        </a>
    </div>

    {{-- FORMULÁRIO --}}
    <form action="{{ route('leads.update', $lead->id) }}" method="POST" class="bg-gray-800 rounded-lg p-6">
        @csrf
        @method('PUT')

        {{-- Título do Negócio --}}
        <div class="mb-4">
            <label class="block text-white font-medium mb-2">Título do Negócio *</label>
            <input type="text" 
                   name="title" 
                   value="{{ old('title', $lead->title) }}"
                   required
                   class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            @error('title')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- Cliente --}}
        <div class="mb-4">
            <label class="block text-white font-medium mb-2">Cliente *</label>
            <select name="client_id" 
                    required
                    class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ $lead->client_id == $client->id ? 'selected' : '' }}>
                        {{ $client->name }} - {{ $client->company_name ?? 'Particular' }}
                    </option>
                @endforeach
            </select>
            @error('client_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- Valor --}}
        <div class="mb-4">
            <label class="block text-white font-medium mb-2">Valor Estimado (R$)</label>
            <input type="number" 
                   name="value" 
                   value="{{ old('value', $lead->value) }}"
                   step="0.01"
                   min="0"
                   class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        {{-- Status --}}
        <div class="mb-4">
            <label class="block text-white font-medium mb-2">Status</label>
            <select name="status" 
                    class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="new" {{ $lead->status == 'new' ? 'selected' : '' }}>Novo</option>
                <option value="negotiation" {{ $lead->status == 'negotiation' ? 'selected' : '' }}>Em Negociação</option>
                <option value="won" {{ $lead->status == 'won' ? 'selected' : '' }}>Ganho</option>
                <option value="lost" {{ $lead->status == 'lost' ? 'selected' : '' }}>Perdido</option>
            </select>
        </div>

        {{-- ENDEREÇO --}}
        <div class="border-t border-gray-700 pt-4 mt-6 mb-4">
            <h3 class="text-white font-bold mb-3">Endereço da Entrega</h3>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-400 text-sm mb-1">CEP</label>
                    <input type="text" name="cep" value="{{ old('cep', $lead->cep) }}"
                           class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-1">Cidade</label>
                    <input type="text" name="city" value="{{ old('city', $lead->city) }}"
                           class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-400 text-sm mb-1">Endereço</label>
                <input type="text" name="address" value="{{ old('address', $lead->address) }}"
                       class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-gray-400 text-sm mb-1">Estado (UF)</label>
                <input type="text" name="state" value="{{ old('state', $lead->state) }}" maxlength="2"
                       class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </div>

        {{-- BOTÕES --}}
        <div class="flex gap-3 mt-6">
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                Salvar Alterações
            </button>
            <a href="{{ route('leads.show', $lead->id) }}" 
               class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition">
                Cancelar
            </a>
        </div>
    </form>

</div>
@endsection