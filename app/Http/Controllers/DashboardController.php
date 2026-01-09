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

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        // Se não digitou nada, redireciona pro dashboard
        if (!$query) {
            return redirect()->route('dashboard');
        }
        
        // ===== BUSCA EM CLIENTES =====
        $clients = \App\Models\Client::where('user_id', auth()->id())
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                ->orWhere('company_name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('phone', 'like', "%{$query}%")
                ->orWhere('address', 'like', "%{$query}%")
                ->orWhere('city', 'like', "%{$query}%");
            })
            ->limit(20)
            ->get();
        
        // ===== BUSCA EM NEGÓCIOS/LEADS =====
        $leads = \App\Models\Lead::where('user_id', auth()->id())
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                ->orWhereHas('client', function($q2) use ($query) {
                    $q2->where('name', 'like', "%{$query}%")
                        ->orWhere('company_name', 'like', "%{$query}%");
                });
                
                // Se buscou por valor (ex: "1000", "R$ 1000")
                $numericQuery = preg_replace('/[^0-9.]/', '', $query);
                if (is_numeric($numericQuery) && $numericQuery > 0) {
                    $q->orWhere('value', '>=', $numericQuery * 0.9)
                    ->where('value', '<=', $numericQuery * 1.1);
                }
                
                // Se buscou por status
                $statusMap = [
                    'novo' => 'new',
                    'novos' => 'new',
                    'negociacao' => 'negotiation',
                    'negociação' => 'negotiation',
                    'ganho' => 'won',
                    'ganhos' => 'won',
                    'fechado' => 'won',
                    'perdido' => 'lost',
                    'perdidos' => 'lost',
                ];
                $lowerQuery = strtolower($query);
                if (isset($statusMap[$lowerQuery])) {
                    $q->orWhere('status', $statusMap[$lowerQuery]);
                }
            })
            ->with('client')
            ->limit(20)
            ->get();
        
        // ===== ESTATÍSTICAS DA BUSCA =====
        $totalResults = $clients->count() + $leads->count();
        $totalValue = $leads->sum('value');
        
        return view('search', compact('query', 'clients', 'leads', 'totalResults', 'totalValue'));
    }
}