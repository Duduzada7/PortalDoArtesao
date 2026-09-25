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
        'Dia',                    
        'DataFim',
        'Rua',                    
        'Bairro',                 
        'Numero',                 
        'Vagas',                  
        'status',                 
        'idADM',                  
        'especialidades_vagas',   
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