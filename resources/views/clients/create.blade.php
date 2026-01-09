@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-3xl">
    
    {{-- CABEÇALHO --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-white">Novo Cliente</h1>
        <a href="{{ route('clients.index') }}" class="text-gray-400 hover:text-white">
            ← Voltar
        </a>
    </div>

    {{-- FORMULÁRIO --}}
    <form action="{{ route('clients.store') }}" method="POST" class="bg-gray-800 rounded-lg p-6">
        @csrf

        {{-- Nome --}}
        <div class="mb-4">
            <label class="block text-white font-medium mb-2">Nome Completo *</label>
            <input type="text" 
                   name="name" 
                   value="{{ old('name') }}"
                   placeholder="João da Silva"
                   required
                   class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label class="block text-white font-medium mb-2">Email *</label>
            <input type="email" 
                   name="email" 
                   value="{{ old('email') }}"
                   placeholder="joao@email.com"
                   required
                   class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- Telefone --}}
        <div class="mb-4">
            <label class="block text-white font-medium mb-2">Telefone</label>
            <input type="text" 
                   name="phone" 
                   value="{{ old('phone') }}"
                   placeholder="(11) 99999-9999"
                   class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        {{-- Empresa --}}
        <div class="mb-4">
            <label class="block text-white font-medium mb-2">Empresa</label>
            <input type="text" 
                   name="company_name" 
                   value="{{ old('company_name') }}"
                   placeholder="Nome da Empresa"
                   class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        {{-- ENDEREÇO --}}
        <div class="border-t border-gray-700 pt-4 mt-6 mb-4">
            <h3 class="text-white font-bold mb-3">Endereço (Opcional)</h3>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-400 text-sm mb-1">CEP</label>
                    <input type="text" name="cep" value="{{ old('cep') }}"
                           placeholder="12345-678"
                           class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-1">Cidade</label>
                    <input type="text" name="city" value="{{ old('city') }}"
                           placeholder="São Paulo"
                           class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-400 text-sm mb-1">Endereço</label>
                <input type="text" name="address" value="{{ old('address') }}"
                       placeholder="Rua, número, complemento"
                       class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-gray-400 text-sm mb-1">Estado (UF)</label>
                <input type="text" name="state" value="{{ old('state') }}" maxlength="2"
                       placeholder="SP"
                       class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </div>

        {{-- BOTÕES --}}
        <div class="flex gap-3 mt-6">
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                Cadastrar Cliente
            </button>
            <a href="{{ route('clients.index') }}" 
               class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition">
                Cancelar
            </a>
        </div>
    </form>

</div>
@endsection