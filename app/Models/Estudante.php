<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Estudante extends Model{
    
  public $timestamps = false; // Nao registrar data/hora criacao/alteracao

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
      'estudanteId' => 'integer',
      'dataHoraExclusao' => 'datetime',
  ];

  protected $table = 'Estudante';
  protected $primaryKey = 'estudanteId';

  protected $estudanteId; // PK Estudante
  protected $dataHoraExclusao;
}
