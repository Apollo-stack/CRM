<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 
        'name', 
        'email', 
        'phone', 
        'company_name',
        'cep',       // <--- Verifique se adicionou esses 4
        'address',   // <---
        'city',      // <---
        'state'      // <---
    ];      
    
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new \App\Models\Scopes\UserScope);
    }

    /**
     * Scope para busca global de clientes
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('company_name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('address', 'like', "%{$term}%")
              ->orWhere('city', 'like', "%{$term}%");
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function latestNote()
    {
        return $this->hasOneThrough(Note::class, Lead::class)
            ->orderBy('notes.created_at', 'desc');
    }
}
