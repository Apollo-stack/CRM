<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\LeadStatus;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'client_id', 
        'title', 
        'value', 
        'status', 
        'stage',
        // Novos campos:
        'cep',
        'address',
        'city',
        'state'
    ];

    /**
     * Casts para tipos especiais
     */
    protected function casts(): array
    {
        return [
            'status' => LeadStatus::class,
            'value' => 'decimal:2',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new \App\Models\Scopes\UserScope);
    }

    /**
     * Scope para busca global de leads
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhereHas('client', function($q2) use ($term) {
                  $q2->where('name', 'like', "%{$term}%")
                     ->orWhere('company_name', 'like', "%{$term}%");
              });
            
            // Busca por valor numérico (com margem de 10%)
            $numericQuery = preg_replace('/[^0-9.]/', '', $term);
            if (is_numeric($numericQuery) && $numericQuery > 0) {
                $q->orWhereBetween('value', [$numericQuery * 0.9, $numericQuery * 1.1]);
            }
            
            // Busca por Status (Mapeamento inteligente)
            $statusMap = [
                'novo' => \App\LeadStatus::NEW,
                'negociacao' => \App\LeadStatus::NEGOTIATION,
                'negociação' => \App\LeadStatus::NEGOTIATION,
                'ganho' => \App\LeadStatus::WON,
                'ganhos' => \App\LeadStatus::WON,
                'fechado' => \App\LeadStatus::WON,
                'perdido' => \App\LeadStatus::LOST,
            ];
            
            $lowerTerm = strtolower($term);
            if (isset($statusMap[$lowerTerm])) {
                $q->orWhere('status', $statusMap[$lowerTerm]);
            }
        });
    }

    // --- ADICIONE ISSO AQUI EMBAIXO ---
    
    // Um Negócio (Lead) "Pertence a" um Cliente
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Um Negócio (Lead) "Pertence a" um Vendedor (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notes()
    {
        // Pega as notas ordenadas da mais recente para a mais antiga
        return $this->hasMany(Note::class)->latest();
    }
}