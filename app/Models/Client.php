<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
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
