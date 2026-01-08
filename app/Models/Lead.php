<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'client_id', 'title', 'value', 'status', 'stage'];

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