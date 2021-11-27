<?php

namespace App\Models\Publicacao;

use \Illuminate\Database\Eloquent\Model;


class Publicacao_Favorita extends Model {
    
    // Nao registrar data/hora criacao/alteracao
    public $timestamps = false;

    protected $table = 'Publicacaoes_favoritas';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
         'publicacao_id' => 'integer',
         'estudante_id' => 'integer'
    ];

    protected $publicacao_id; // FK Publicacoes
    protected $estudante_id; // FK Estudantes

}
