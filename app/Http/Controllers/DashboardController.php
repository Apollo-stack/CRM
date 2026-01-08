<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ===== MÉTRICAS BÁSICAS =====
        $totalClientes = Client::count();
        $negociosAbertos = Lead::whereIn('status', ['NEW', 'NEGOTIATION'])->count();
        $totalVendido = Lead::where('status', 'WON')->sum('value');

        // ===== TAXA DE CONVERSÃO =====
        $totalNegocios = Lead::count();
        $negociosGanhos = Lead::where('status', 'WON')->count();
        $taxaConversao = $totalNegocios > 0 ? round(($negociosGanhos / $totalNegocios) * 100, 1) : 0;

        // ===== TICKET MÉDIO =====
        $ticketMedio = $negociosGanhos > 0 ? round($totalVendido / $negociosGanhos, 2) : 0;

        // ===== GRÁFICO DE VENDAS (ÚLTIMOS 6 MESES) =====
        $vendasPorMes = Lead::where('status', 'WON')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'),
                DB::raw('SUM(value) as total')
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Formatar dados para o gráfico
        $meses = [];
        $valores = [];
        foreach ($vendasPorMes as $venda) {
            // Converter "2025-01" para "Jan/25"
            $meses[] = date('M/y', strtotime($venda->mes . '-01'));
            $valores[] = $venda->total;
        }

        // ===== DISTRIBUIÇÃO DO FUNIL =====
        $distribuicaoFunil = [
            'novos' => Lead::where('status', 'NEW')->count(),
            'negociacao' => Lead::where('status', 'NEGOTIATION')->count(),
            'ganhos' => Lead::where('status', 'WON')->count(),
            'perdidos' => Lead::where('status', 'LOST')->count(),
        ];

        return view('dashboard', compact(
            'totalClientes',
            'negociosAbertos',
            'totalVendido',
            'taxaConversao',
            'ticketMedio',
            'meses',
            'valores',
            'distribuicaoFunil'
        ));
    }
}