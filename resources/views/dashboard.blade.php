@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Dashboard Analítico</h1>
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Visão Geral do Sistema
        </div>
    </div>

    {{-- GRID DE KPIS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Ticket Médio --}}
        <x-kpi-card title="Ticket Médio" value="R$ {{ number_format($ticketMedio, 2, ',', '.') }}" color="orange" icon="
            <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'></path></svg>
        " />

        {{-- Negócios Ativos --}}
        <x-kpi-card title="Negócios em Aberto" value="{{ $negociosAbertos }}" color="yellow" icon="
            <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'></path></svg>
        " />

        {{-- Receita Total --}}
        <x-kpi-card title="Total Vendido" value="R$ {{ number_format($totalVendido, 2, ',', '.') }}" color="green" icon="
            <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'></path></svg>
        " />

        {{-- Taxa de Conversão --}}
        <x-kpi-card title="Conversão Global" value="{{ $taxaConversao }}%" color="purple" trend="{{ $taxaConversao > 20 ? 5 : -2 }}" icon="
            <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'></path></svg>
        " />
    </div>

    {{-- ÁREA DE GRÁFICOS --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        {{-- Gráfico Principal (Evolução) --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Evolução de Vendas (6 Meses)</h3>
            <div id="salesChart" style="min-height: 350px;"></div>
        </div>

        {{-- Gráfico Secundário (Status) --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Distribuição do Pipeline</h3>
            <div id="statusChart" style="min-height: 350px;"></div>
        </div>
    </div>

    {{-- ACESSO RÁPIDO --}}
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-xl p-8 text-white shadow-lg">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h3 class="text-2xl font-bold mb-2">Acesso Rápido</h3>
                <p class="text-gray-400">Inicie novas atividades com um clique.</p>
            </div>
            <div class="flex gap-4 mt-4 md:mt-0">
                <a href="{{ route('clients.create') }}" class="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'></path></svg>
                    Novo Cliente
                </a>
                <a href="{{ route('leads.create') }}" class="flex items-center bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg font-medium transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'></path></svg>
                    Novo Negócio
                </a>
            </div>
        </div>
    </div>
</div>

{{-- APEXCHARTS --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // --- GRÁFICO DE VENDAS (AREA) ---
        const salesOptions = {
            series: [{
                name: 'Vendas Realizadas',
                data: @json($valores)
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: { show: false },
                fontFamily: 'Figtree, sans-serif',
                background: 'transparent'
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            xaxis: {
                categories: @json($meses),
                labels: { style: { colors: '#9ca3af' } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    style: { colors: '#9ca3af' },
                    formatter: (value) => { return 'R$ ' + value.toLocaleString('pt-BR'); }
                }
            },
            colors: ['#10b981'], // Emerald 500
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            grid: {
                borderColor: '#374151', // Gray 700
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            theme: { mode: 'dark' } // Force dark mode theme adaptation if needed or handle conditionally
        };

        const salesChart = new ApexCharts(document.querySelector("#salesChart"), salesOptions);
        salesChart.render();

        // --- GRÁFICO DE STATUS (DONUT) ---
        // Preparando dados do array associativo PHP
        const funnelData = @json($distribuicaoFunil);
        const funnelLabels = ['Novos', 'Negociação', 'Ganhos', 'Perdidos'];
        const funnelSeries = [
            funnelData.novos || 0, 
            funnelData.negociacao || 0, 
            funnelData.ganhos || 0, 
            funnelData.perdidos || 0
        ];

        const statusOptions = {
            series: funnelSeries,
            labels: funnelLabels,
            chart: {
                type: 'donut',
                height: 350,
                fontFamily: 'Figtree, sans-serif',
                background: 'transparent'
            },
            colors: ['#3b82f6', '#eab308', '#10b981', '#ef4444'], // Blue, Yellow, Green, Red
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                color: '#9ca3af',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            },
            legend: {
                position: 'bottom',
                labels: { colors: '#9ca3af' }
            },
            dataLabels: { enabled: false },
            stroke: { show: false }
        };

        const statusChart = new ApexCharts(document.querySelector("#statusChart"), statusOptions);
        statusChart.render();
    });
</script>
@endsection