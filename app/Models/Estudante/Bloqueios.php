<?php

namespace App\Models\Estudante;

use \Illuminate\Database\Eloquent\Model;

class Bloqueios extends Model {
  
  // Registrar data/hora criacao/alteracao
  public $timestamps = true;


  protected $casts = [
    'id' => 'integer',
    'motivo' => 'string',
    'created_at' => 'datetime',
    'finished_at' => 'datetime',
    'estudante_id' => 'integer',
  ];

  protected $id; // PK
  protected $motivo;
  protected $created_at;
  protected $finished_at;
  protected $estudante_at; // FK Estudante

}
