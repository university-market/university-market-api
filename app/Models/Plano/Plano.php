<?php

namespace App\Models\Plano;

use \Illuminate\Database\Eloquent\Model;

class Plano extends Model {
    
  public $timestamps = false; // Nao registrar data/hora criacao/alteracao

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'planoId' => 'integer',
    'nome' => 'string'
  ];

  protected $table = 'Plano';
  protected $primaryKey = 'planoId';

  protected $planoId; // PK Plano
  protected $nome;
}
