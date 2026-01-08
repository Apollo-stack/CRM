<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Inicia a query
        $query = Client::query();

        // ===== BUSCA GLOBAL =====
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('company', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // ===== FILTRO POR EMPRESA =====
        if ($request->filled('company')) {
            $query->where('company', 'like', "%{$request->company}%");
        }

        // ===== ORDENAÇÃO =====
        $orderBy = $request->get('order_by', 'name'); // padrão: nome
        $orderDirection = $request->get('order_direction', 'asc'); // padrão: crescente
        
        $query->orderBy($orderBy, $orderDirection);

        // ===== PAGINAÇÃO (15 por página) =====
        $clients = $query->paginate(15)->appends($request->all());

        // ===== BUSCAR ÚLTIMA INTERAÇÃO DE CADA CLIENTE =====
        foreach ($clients as $client) {
            // Pega a última nota/interação relacionada aos leads deste cliente
            $ultimaInteracao = \App\Models\Note::whereHas('lead', function($q) use ($client) {
                $q->where('client_id', $client->id);
            })->latest()->first();
            
            $client->ultima_interacao = $ultimaInteracao ? $ultimaInteracao->created_at : null;
        }

        // Pegar lista única de empresas para o filtro
        $empresas = Client::distinct()->pluck('company')->filter()->sort();

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
    public function store(Request $request)
    {
        // 1. Validar os dados (Segurança básica)
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email',
            'cep' => 'nullable',
            'address' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable|max:2',
        ]);

        // 2. Criar o cliente no Banco
        // A gente usa o Model 'Client' pra isso
        \App\Models\Client::create([
            'user_id' => auth()->id(), // Pega o ID do usuário logado automaticamente
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
        ]);

        // 3. Redirecionar para a lista com mensagem de sucesso
        return redirect()->route('clients.index')->with('success', 'Cliente cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Busca o cliente
        $client = \App\Models\Client::where('user_id', auth()->id())->findOrFail($id);

        // Busca o histórico de vendas (Negócios ganhos)
        $salesHistory = \App\Models\Lead::where('client_id', $client->id)
                                        ->where('status', 'won')
                                        ->latest()
                                        ->get();

        // Busca negócios em aberto (pra você ver o que está rolando agora)
        $openLeads = \App\Models\Lead::where('client_id', $client->id)
                                     ->whereIn('status', ['new', 'negotiation'])
                                     ->latest()
                                     ->get();

        return view('clients.show', compact('client', 'salesHistory', 'openLeads'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = \App\Models\Client::where('user_id', auth()->id())->findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $client = \App\Models\Client::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'company_name' => 'nullable',
            'cep' => 'nullable',
            'address' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable|max:2',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = \App\Models\Client::where('user_id', auth()->id())->findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente excluído com sucesso!');
    }

    public function obterDadosJson($id)
    {
        // Busca o cliente ou falha se não existir
        $cliente = \App\Models\Client::findOrFail($id);

        // Retorna os dados como JSON para o Javascript ler
        return response()->json([
            'cep' => $cliente->cep,
            'cidade' => $cliente->cidade,
            'uf' => $cliente->uf,
            'endereco_completo' => $cliente->endereco . ', ' . $cliente->numero // Exemplo de concatenação
        ]);
    }

    public function buscaEndereco($id)
    {
        // 1. Tenta achar o cliente
        $cliente = \App\Models\Client::find($id);

        // 2. Se não achar, devolve erro
        if (!$cliente) {
            return response()->json(['erro' => 'Cliente não encontrado'], 404);
        }

        // 3. Devolve os dados bonitinhos
        return response()->json([
            'cep' => $cliente->cep,
            'endereco' => $cliente->address,
            'numero' => $cliente->number,
            'bairro' => $cliente->neighborhood,
            'cidade' => $cliente->city,
            'estado' => $cliente->state, // ou 'uf', confira como está no seu banco
        ]);
    }
}
