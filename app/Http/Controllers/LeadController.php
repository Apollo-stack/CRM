<?php

namespace App\Http\Controllers;
use App\Models\Note;
use Illuminate\Http\Request;
use App\Models\Lead;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Comça a query base (Global Scope filtra automaticamente por user_id)
        $query = \App\Models\Lead::with('client')->latest();

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

        Lead::create([
            'user_id' => auth()->id(),
            'client_id' => $request->client_id,
            'title' => $request->title,
            'value' => $request->value ?? 0,
            'status' => 'new',
            'cep' => $request->cep,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
        ]);

        return redirect()->route('leads.index')
            ->with('success', 'Negócio criado com sucesso!');
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
        $lead = Lead::where('user_id', auth()->id())->findOrFail($id);

        // CORREÇÃO: Só entra no "Modo Rápido" se tiver status E NÃO tiver title.
        // Isso evita que o formulário de edição caia aqui por engano.
        if ($request->has('status') && !$request->has('title')) {
            $lead->status = $request->status;
            $lead->save();
            
            $messages = [
                'negotiation' => 'Negócio movido para Em Negociação!',
                'won' => '🎉 Parabéns! Negócio marcado como Ganho!',
                'lost' => 'Negócio marcado como Perdido.',
                'new' => 'Negócio voltou para Novos.',
            ];
            
            return back()->with('success', $messages[$request->status] ?? 'Status atualizado!');
        }

        // Edição completa do formulário
        $request->validate([
            'title' => 'required',
            'value' => 'numeric',
            'client_id' => 'required'
        ]);

        $lead->update($request->only([
            'title', 'value', 'client_id', 'status',
            'cep', 'address', 'city', 'state'
        ]));

        return redirect()->route('leads.show', $lead->id)
            ->with('success', 'Negócio atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lead = Lead::where('user_id', auth()->id())->findOrFail($id);
        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Negócio excluído com sucesso!');
    }

    public function storeNote(Request $request, $id)
    {
        $request->validate(['content' => 'required']);

        Note::create([
            'lead_id' => $id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'type' => $request->type ?? 'note'
        ]);

        $typeMessages = [
            'call' => '📞 Ligação registrada!',
            'whatsapp' => '💬 Mensagem do WhatsApp registrada!',
            'email' => '📧 Email registrado!',
            'meeting' => '🤝 Reunião registrada!',
            'note' => '📝 Nota adicionada!',
        ];

        return back()->with('success', $typeMessages[$request->type] ?? 'Interação registrada!');
    }
}
