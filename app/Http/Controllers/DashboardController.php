<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Lead;
use App\LeadStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ===== MÉTRICAS BÁSICAS =====
        $totalClientes = Client::count();
        
        // Uso de ENUM para segurança e clareza
        $negociosAbertos = Lead::whereIn('status', [LeadStatus::NEW, LeadStatus::NEGOTIATION])->count();
        $totalVendido = Lead::where('status', LeadStatus::WON)->sum('value');

        // ===== TAXA DE CONVERSÃO =====
        $totalNegocios = Lead::count();
        $negociosGanhos = Lead::where('status', LeadStatus::WON)->count();
        $taxaConversao = $totalNegocios > 0 ? round(($negociosGanhos / $totalNegocios) * 100, 1) : 0;

        // ===== TICKET MÉDIO =====
        $ticketMedio = $negociosGanhos > 0 ? round($totalVendido / $negociosGanhos, 2) : 0;

        // ===== GRÁFICO DE VENDAS (ÚLTIMOS 6 MESES) =====
        $vendasPorMes = Lead::where('status', LeadStatus::WON)
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
            $meses[] = date('M/y', strtotime($venda->mes . '-01'));
            $valores[] = $venda->total;
        }

        // ===== DISTRIBUIÇÃO DO FUNIL =====
        $distribuicaoFunil = [
            'novos' => Lead::where('status', LeadStatus::NEW)->count(),
            'negociacao' => Lead::where('status', LeadStatus::NEGOTIATION)->count(),
            'ganhos' => Lead::where('status', LeadStatus::WON)->count(),
            'perdidos' => Lead::where('status', LeadStatus::LOST)->count(),
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

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return redirect()->route('dashboard');
        }
        
        // Busca simplificada usando Scopes criados nos Models
        $clients = Client::search($query)->limit(20)->get();
        $leads = Lead::search($query)->with('client')->limit(20)->get();
        
        // ===== ESTATÍSTICAS DA BUSCA =====
        $totalResults = $clients->count() + $leads->count();
        $totalValue = $leads->sum('value');
        
        return view('search', compact('query', 'clients', 'leads', 'totalResults', 'totalValue'));
    }
}