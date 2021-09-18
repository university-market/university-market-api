<?php

namespace App\Models\Session;

use Illuminate\Database\Eloquent\Model;

class BaseSession extends Model {

  public $timestamps = false; // Nao registrar data/hora criacao/alteracao

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'sessionId' => 'integer',
    'usuarioId' => 'integer',
    'sessionToken' => 'string',
    'dataHoraExpiracao' => 'datetime',
  ];

  protected $primaryKey = 'sessionId';

  protected $sessionId; // PK Session
  protected $usuarioId; // FK Tabela Usuario
  protected $sessionToken;
  protected $dataHoraExpiracao;

}