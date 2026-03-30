<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    use HasFactory;

    /**
     * Os atributos que são atribuíveis em massa (mass assignment).
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'descricao',
        'status',
        'data_finalizacao'
    ];

    /**
     * Os atributos que devem ser convertidos (casts) para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'data_finalizacao' => 'date',  // ou 'datetime' se tiver horas
    ];
}
