<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Artesao extends Model
{
    use HasFactory;

    //Nome da tabela no banco
    protected $table = 'artesao';

    //Chave primaria customizada
    protected $primaryKey = 'ID_Artesao';

    //Campos que podem ser preenchidos em massa
    protected $fillable = [
        'Nome',
        'Telefone',
        'Email',
        'Rua',
        'Numero',
        'Bairro',
        'Nivel',
        'posicao_fila',
        'StatusAprovacao',
        'Aprovado_por',
    ];

    //Relacionamento N:N com Especialidades
    public function especialidades(){
        return $this->belongsToMany(
            Especialidades::class,
            'artesao_especialidade', //tabela pivô
            'ID_Artesao',            //fk deste model na pivô
            'ID_Especialidade'       //fk do outro model na pivô
        )->withTimestamps();
    }

    //Relacionamento N:N com Eventos (Candidaturas)
    public function eventos(){
        return $this->belongsToMany(
            Evento::class,
            'candidatura', //tabela pivô
            'ID_Artesao',  //fk deste model na pivô
            'ID_Evento',   //fk do outro model na pivô 
        )->withPivot('StatusDaCandidatura') //traz a coluna extra da pivô
        ->withTimestamps();
    }
}
