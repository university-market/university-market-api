<?php

namespace App\Models\Estudante;

use \Illuminate\Database\Eloquent\Model;

class Bloqueios extends Model {
  
  // Registrar data/hora criacao/alteracao
  public $timestamps = true;

  /**
   * The attributes that should be cast.
   *
   * @var array
   */

  protected $casts = [
    'id' => 'integer',
    'motivo' => 'string',
    'finished_at' => 'datetime',
    'estudante_id' => 'integer',
  ];

  protected $table = 'bloqueios';
  protected $primaryKey = 'id';

  protected $id; // PK
  protected $motivo;
  protected $created_at;
  protected $finished_at;
  protected $estudante_id; // FK Estudante
  protected $updated_at;
  
}
