<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'eventos';
    protected $primaryKey = 'ID_Evento';
    public $timestamps = true; // Alterado para true pois existem created_at e updated_at no banco

    protected $fillable = [
        'Nome',
        'Descricao',
        'Dia',                    // Nome correto no banco de dados
        'DataFim',
        'Rua',                    // Nome correto no banco de dados[cite: 5]
        'Bairro',                 // Nome correto no banco de dados[cite: 5]
        'Numero',                 // Nome correto no banco de dados[cite: 5]
        'Vagas',                  // Nome correto no banco de dados[cite: 5]
        'status',                 // Em minúsculo como no banco[cite: 5]
        'idADM',                  // Nome correto no banco de dados[cite: 5]
        'especialidades_vagas',   // Nome correto no banco de dados[cite: 5]
    ];

    public function artesaos()
    {
        return $this->belongsToMany(
            Artesao::class,
            'candidatura',
            'ID_Evento',
            'ID_Artesao'
        )->withPivot('StatusDaCandidatura')
         ->withTimestamps();
    }
}