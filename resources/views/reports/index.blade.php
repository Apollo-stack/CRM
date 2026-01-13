@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-white mb-8">Exportação e Relatórios</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- CARD: CLIENTES --}}
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="flex items-center gap-4 mb-4">
                <div class="bg-blue-900 p-3 rounded-full text-blue-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">Base de Clientes</h2>
                    <p class="text-gray-400 text-sm">Exporte a lista completa de clientes cadastrados.</p>
                </div>
            </div>
            
            <div class="mt-4 p-4 bg-gray-900 rounded text-sm text-gray-300 mb-6">
                <strong>Campos exportados:</strong><br>
                Nome, Empresa, Email, Telefone, Cidade/UF, Data de Cadastro.
            </div>

            <a href="{{ route('reports.clients') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-bold py-3 rounded-lg transition">
                📥 Baixar CSV de Clientes
            </a>
        </div>

        {{-- CARD: VENDAS (LEADS) --}}
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="flex items-center gap-4 mb-4">
                <div class="bg-green-900 p-3 rounded-full text-green-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">Relatório de Vendas</h2>
                    <p class="text-gray-400 text-sm">Exporte dados do pipeline de vendas com filtros.</p>
                </div>
            </div>

            <form action="{{ route('reports.leads') }}" method="GET" class="space-y-4">
                
                {{-- Filtro Status --}}
                <div>
                    <label class="block text-gray-400 text-sm mb-1">Status do Negócio</label>
                    <select name="status" class="w-full bg-gray-700 text-white rounded p-2 text-sm border-gray-600 focus:ring-green-500 font-medium">
                        <option value="all">Todos os Status</option>
                        <option value="won">✅ Ganhos (Vendas Realizadas)</option>
                        <option value="negotiation">🔵 Em Negociação</option>
                        <option value="new">⚪ Novos</option>
                        <option value="lost">🔴 Perdidos</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Filtro Data Inicial --}}
                    <div>
                        <label class="block text-gray-400 text-sm mb-1">De:</label>
                        <input type="date" name="date_start" class="w-full bg-gray-700 text-white rounded p-2 text-sm border-gray-600 focus:ring-green-500">
                    </div>
                    
                    {{-- Filtro Data Final --}}
                    <div>
                        <label class="block text-gray-400 text-sm mb-1">Até:</label>
                        <input type="date" name="date_end" class="w-full bg-gray-700 text-white rounded p-2 text-sm border-gray-600 focus:ring-green-500">
                    </div>
                </div>

                <button type="submit" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center font-bold py-3 rounded-lg transition mt-4">
                    📥 Baixar Relatório de Vendas
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
