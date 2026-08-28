<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'eventos';
    protected $primaryKey = 'ID_Evento';
    public $timestamps = false; // Ajuste para true se usar created_at/updated_at

    protected $fillable = [
        'Nome',
        'DataInicio',
        'DataFim',
        'Localizacao',
        'Descricao',
        'Status',
        'ID_ADM'
    ];

    // Relacionamento Muitos para Muitos com Artesao
   // Na Model App\Models\Evento.php

public function artesaos()
{
    return $this->belongsToMany(
        Artesao::class,
        'candidatura',            // Nome exato da sua tabela pivô
        'ID_Evento',              // Chave desta model na pivô
        'ID_Artesao'              // Chave da model Artesao na pivô
    )->withPivot('StatusDaCandidatura')
     ->withTimestamps();
}
}