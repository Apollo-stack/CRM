<?php

namespace App\Http\Controllers;
use App\Models\Note;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Começa a query base
        $query = \App\Models\Lead::with('client')->latest();

        // Se NÃO tiver pedindo pra ver "todos", filtra só os meus (Padrão)
        if ($request->input('view') !== 'all') {
            $query->where('user_id', auth()->id());
        }

        // Pega os resultados
        $leads = $query->get();
        
        return view('leads.index', compact('leads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Busca todos os clientes para preencher o <select>
        $clients = \App\Models\Client::where('user_id', auth()->id())->get();

        return view('leads.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'client_id' => 'required|exists:clients,id',
        ]);

        \App\Models\Lead::create([
            'user_id' => auth()->id(),
            'client_id' => $request->client_id,
            'title' => $request->title,
            'value' => $request->value ?? 0,
            'status' => 'new',
            
            // Salvando o endereço
            'cep' => $request->cep,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
        ]);

        return redirect()->route('leads.index')->with('success', 'Negócio criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Busca o Lead pelo ID, trazendo os dados do Cliente junto
        $lead = \App\Models\Lead::where('user_id', auth()->id())
                    ->with('client')
                    ->findOrFail($id);
        
        return view('leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lead = \App\Models\Lead::where('user_id', auth()->id())->findOrFail($id);
        $clients = \App\Models\Client::where('user_id', auth()->id())->get();
        
        return view('leads.edit', compact('lead', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'client_id' => 'required|exists:clients,id',
        ]);

        \App\Models\Lead::create([
            'user_id' => auth()->id(),
            'client_id' => $request->client_id,
            'title' => $request->title,
            'value' => $request->value ?? 0,
            'status' => 'new',
            
            // Salvando o endereço
            'cep' => $request->cep,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
        ]);

        return redirect()->route('leads.index')->with('success', 'Negócio criado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function storeNote(Request $request, $id)
    {
        $request->validate(['content' => 'required']);

        \App\Models\Note::create([
            'lead_id' => $id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'type' => $request->type ?? 'note'
        ]);

        return back()->with('success', 'Anotação adicionada!');
    }
}
