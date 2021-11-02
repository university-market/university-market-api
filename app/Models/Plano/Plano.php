<?php

namespace App\Models\Plano;

use \Illuminate\Database\Eloquent\Model;

class Plano extends Model {
  
  // Nao registrar data/hora criacao/alteracao
  public $timestamps = false;

  // protected $table = 'Planos';
  // protected $primaryKey = 'id';

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'nome' => 'string'
  ];

  protected $id; // PK
  protected $nome;
}
