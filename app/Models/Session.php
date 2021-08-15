<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Session extends Model{
    
    public $timestamps = false; // Nao registrar data/hora criacao/alteracao

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sessionId' => 'integer',
        'usuarioId' => 'integer',
        'sessionTipoId' => 'integer',
        'sessionToken' => 'string',
        'dataHoraExpiracao' => 'datetime',
    ];

    protected $table = 'SessionTable';
    protected $primaryKey = 'sessionId';

    protected $sessionId; // PK Session
    protected $usuarioId; // FK Tabela Usuario
    protected $sessionTipoId; // FK Tabela Session Tipo
    protected $sessionToken;
    protected $dataHoraExpiracao;
}
