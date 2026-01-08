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
            'client_id' => 'required|exists:clients,id', // Garante que o cliente existe
        ]);

        \App\Models\Lead::create([
            'user_id' => auth()->id(),
            'client_id' => $request->client_id,
            'title' => $request->title,
            'value' => $request->value ?? 0,
            'status' => 'new', // Todo negócio começa como 'novo'
        ]);

        // Redireciona para onde você quiser (por enquanto vamos voltar pra lista de clientes ou criar uma de leads depois)
        return redirect()->route('clients.index')->with('success', 'Negócio criado com sucesso!');
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
    public function update(Request $request, string $id)
    {
        $lead = \App\Models\Lead::where('user_id', auth()->id())->findOrFail($id);

        // Se o request tiver 'status', é aquela mudança rápida de kanban/botões
        if ($request->has('status')) {
            $lead->status = $request->status;
            $lead->save();
            return back();
        }

        // Se não, é uma edição completa do formulário
        $request->validate([
            'title' => 'required',
            'value' => 'numeric',
            'client_id' => 'required'
        ]);

        $lead->update($request->only(['title', 'value', 'client_id']));

        return redirect()->route('leads.show', $lead->id)->with('success', 'Negócio atualizado!');
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
