@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-white mb-8">Dashboard</h1>

    {{-- CARDS DE MÉTRICAS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total de Clientes --}}
        <div class="bg-gray-800 rounded-lg p-6 border-l-4 border-blue-500">
            <p class="text-gray-400 text-sm">Total de Clientes</p>
            <h2 class="text-4xl font-bold text-white mt-2">{{ $totalClientes }}</h2>
        </div>

        {{-- Negócios em Aberto --}}
        <div class="bg-gray-800 rounded-lg p-6 border-l-4 border-yellow-500">
            <p class="text-gray-400 text-sm">Negócios em Aberto</p>
            <h2 class="text-4xl font-bold text-white mt-2">{{ $negociosAbertos }}</h2>
        </div>

        {{-- Total Vendido --}}
        <div class="bg-gray-800 rounded-lg p-6 border-l-4 border-green-500">
            <p class="text-gray-400 text-sm">Total Vendido (R$)</p>
            <h2 class="text-4xl font-bold text-green-400 mt-2">R$ {{ number_format($totalVendido, 2, ',', '.') }}</h2>
        </div>

        {{-- Taxa de Conversão --}}
        <div class="bg-gray-800 rounded-lg p-6 border-l-4 border-purple-500">
            <p class="text-gray-400 text-sm">Taxa de Conversão</p>
            <h2 class="text-4xl font-bold text-white mt-2">{{ $taxaConversao }}%</h2>
        </div>
    </div>

    {{-- SEGUNDA LINHA DE CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        {{-- Ticket Médio --}}
        <div class="bg-gray-800 rounded-lg p-6">
            <p class="text-gray-400 text-sm mb-2">Ticket Médio</p>
            <h2 class="text-3xl font-bold text-white">R$ {{ number_format($ticketMedio, 2, ',', '.') }}</h2>
        </div>

        {{-- Distribuição do Funil --}}
        <div class="bg-gray-800 rounded-lg p-6">
            <p class="text-gray-400 text-sm mb-4">Distribuição do Funil</p>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-gray-300">Novos</span>
                    <span class="text-white font-bold">{{ $distribuicaoFunil['novos'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-300">Em Negociação</span>
                    <span class="text-white font-bold">{{ $distribuicaoFunil['negociacao'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-green-400">Ganhos</span>
                    <span class="text-green-400 font-bold">{{ $distribuicaoFunil['ganhos'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-red-400">Perdidos</span>
                    <span class="text-red-400 font-bold">{{ $distribuicaoFunil['perdidos'] }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- GRÁFICO DE VENDAS --}}
    <div class="bg-gray-800 rounded-lg p-6 mb-8">
        <h3 class="text-xl font-bold text-white mb-4">Vendas nos Últimos 6 Meses</h3>
        <canvas id="vendasChart" class="w-full" style="max-height: 400px;"></canvas>
    </div>

    {{-- ACESSO RÁPIDO --}}
    <div class="bg-gray-800 rounded-lg p-6">
        <h3 class="text-xl font-bold text-white mb-4">Acesso Rápido</h3>
        <div class="flex gap-4">
            <a href="{{ route('clients.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                + Cadastrar Cliente
            </a>
            <a href="{{ route('leads.create') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition">
                + Novo Negócio
            </a>
        </div>
    </div>
</div>

{{-- SCRIPT DO GRÁFICO --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dados vindos do controller
    const meses = @json($meses);
    const valores = @json($valores);

    // Configuração do gráfico
    const ctx = document.getElementById('vendasChart').getContext('2d');
    const vendasChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: meses,
            datasets: [{
                label: 'Vendas (R$)',
                data: valores,
                borderColor: '#10b981', // Verde
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4 // Deixa a linha suave
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#fff'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#9ca3af',
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: '#9ca3af'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    });
</script>
@endsection