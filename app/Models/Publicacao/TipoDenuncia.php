<?php

namespace App\Models\Publicacao;

use \Illuminate\Database\Eloquent\Model;

class TipoDenuncia extends Model {
  
  // Registrar data/hora criacao/alteracao
  public $timestamps = false;

  /**
   * The attributes that should be cast.
   *
   * @var array
   */

  protected $casts = [
    'id' => 'integer',
    'descricao' => 'string'
  ];

  protected $table = 'tipos_denuncias';
  protected $primaryKey = 'id';

  protected $id; // PK
  protected $descricao;
}
