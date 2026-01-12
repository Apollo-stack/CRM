<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;      // ← ADICIONA ESTA LINHA
use App\Models\Note;     // ← ADICIONA ESTA LINHA
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Global UserScope applies automatically
        $query = Client::query();

        // ===== BUSCA GLOBAL =====
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('company_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // ===== FILTRO POR EMPRESA =====
        if ($request->filled('company')) {
            $query->where('company_name', 'like', "%{$request->company}%");
        }

        // ===== ORDENAÇÃO =====
        $orderBy = $request->get('order_by', 'name'); // padrão: nome
        $orderDirection = $request->get('order_direction', 'asc'); // padrão: crescente

        // Campos permitidos para ordenação (mapeia "company" para "company_name")
        $allowedOrderFields = [
            'name' => 'name',
            'company' => 'company_name',
            'company_name' => 'company_name',
            'created_at' => 'created_at',
            'email' => 'email'
        ];

        // Se o campo não for permitido, usa 'name' como padrão
        $orderByField = $allowedOrderFields[$orderBy] ?? 'name';

        $query->orderBy($orderByField, $orderDirection);

        // ===== PAGINAÇÃO (15 por página) =====
        // OTIMIZAÇÃO: Carregamos a nota mais recente para evitar N+1
        $clients = $query->with('latestNote')->paginate(15)->appends($request->all());

        // Pegar lista única de empresas para o filtro
        $empresas = Client::distinct()->pluck('company_name')->filter()->sort();

        return view('clients.index', compact('clients', 'empresas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        // Dados já validados e seguros
        $data = $request->validated();
        
        // Adiciona o user_id manualmente
        $data['user_id'] = auth()->id();

        Client::create($data);

        return redirect()->route('clients.index')
            ->with('success', 'Cliente cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = Client::findOrFail($id);
        
        // Busca as vendas ganhas (WON) deste cliente
        // Note: Leads also have UserScope, so we might not strictly need to check client ownership if we trust the lead->client relation, 
        // but strictly ensuring leads belong to the user is safe.
        // Since Leads have UserScope, this query `App\Models\Lead::...` will also be scoped to the user.
        
        $salesHistory = \App\Models\Lead::where('client_id', $client->id)
            ->where('status', 'won')
            ->orderBy('updated_at', 'desc')
            ->get();
        
        $openLeads = \App\Models\Lead::where('client_id', $client->id)
            ->whereIn('status', ['new', 'negotiation'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('clients.show', compact('client', 'salesHistory', 'openLeads'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, string $id)
    {
        $client = Client::findOrFail($id);

        $client->update($request->validated());

        return redirect()->route('clients.show', $client->id)
            ->with('success', 'Dados do cliente atualizados!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Cliente excluído com sucesso!');
    }

    public function obterDadosJson($id)
    {
        // SEGURANÇA: Busca apenas se pertencer ao usuário logado
        $cliente = \App\Models\Client::where('user_id', auth()->id())->findOrFail($id);

        // Retorna os dados como JSON para o Javascript ler
        return response()->json([
            'cep' => $cliente->cep,
            'cidade' => $cliente->cidade,
            'uf' => $cliente->uf,
            'endereco_completo' => $cliente->endereco . ', ' . $cliente->numero
        ]);
    }

    public function buscaEndereco($id)
    {
        // SEGURANÇA: Busca apenas se pertencer ao usuário logado
        $cliente = \App\Models\Client::where('user_id', auth()->id())->find($id);

        // Se não achar ou não pertencer ao usuário, devolve erro
        if (!$cliente) {
            return response()->json(['erro' => 'Cliente não encontrado'], 404);
        }

        // Devolve os dados
        return response()->json([
            'cep' => $cliente->cep,
            'endereco' => $cliente->address,
            'numero' => $cliente->number,
            'bairro' => $cliente->neighborhood,
            'cidade' => $cliente->city,
            'estado' => $cliente->state,
        ]);
    }
}
