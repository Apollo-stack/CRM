<?php

namespace App\Http\Controllers;
use App\Models\Note;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Global Scope filters by user_id automatically
        $query = Lead::with('client')->latest();

        $leads = $query->get();
        
        return view('leads.index', compact('leads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Scope applies to Client too
        $clients = \App\Models\Client::all();

        return view('leads.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeadRequest $request)
    {
        // Validation is handled by StoreLeadRequest

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
        // Scope applies automatically
        $lead = Lead::with('client')->findOrFail($id);
        
        return view('leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lead = Lead::findOrFail($id);
        $clients = \App\Models\Client::all();
        
        return view('leads.edit', compact('lead', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeadRequest $request, string $id)
    {
        $lead = Lead::findOrFail($id);

        // Validation handled by UpdateLeadRequest

        // Logic for Quick Update (only status)
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

        // Full Update
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
        $lead = Lead::findOrFail($id);
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
