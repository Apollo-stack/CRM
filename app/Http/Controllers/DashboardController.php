<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Lead;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // 1. Conta total de clientes do vendedor
        $totalClients = Client::where('user_id', $userId)->count();

        // 2. Conta leads ativos (Novos ou Em negociação)
        $activeLeads = Lead::where('user_id', $userId)
                            ->whereIn('status', ['new', 'negotiation'])
                            ->count();

        // 3. Soma o valor de tudo que foi ganho
        $wonValue = Lead::where('user_id', $userId)
                        ->where('status', 'won')
                        ->sum('value');

        // Manda tudo pra tela
        return view('dashboard', compact('totalClients', 'activeLeads', 'wonValue'));
    }

    // Adicione a função search logo abaixo da index
    public function search(Request $request)
    {
        $query = $request->input('q');

        // Se a busca for vazia, volta pra trás
        if (!$query) {
            return back();
        }

        $userId = auth()->id();

        // 1. Busca Clientes (pelo nome ou email ou empresa)
        $clients = Client::where('user_id', $userId)
                        ->where(function($q) use ($query) {
                            $q->where('name', 'like', "%{$query}%")
                              ->orWhere('email', 'like', "%{$query}%")
                              ->orWhere('company_name', 'like', "%{$query}%");
                        })
                        ->get();

        // 2. Busca Negócios (Pelo título OU pelo nome do cliente)
        $leads = Lead::where('user_id', $userId)
                     ->where(function($q) use ($query) {
                         $q->where('title', 'like', "%{$query}%") // Procura no título
                           ->orWhereHas('client', function($subQuery) use ($query) {
                               $subQuery->where('name', 'like', "%{$query}%"); // Procura no nome do cliente dono do lead
                           });
                     })
                     ->with('client')
                     ->get();
        return view('search.results', compact('clients', 'leads', 'query'));
    }
}