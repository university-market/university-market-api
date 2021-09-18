<?php

namespace App\Models\Curso;

use \Illuminate\Database\Eloquent\Model;

class Curso extends Model {
    
  public $timestamps = false; // Nao registrar data/hora criacao/alteracao

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
      'cursoId' => 'integer',
      'nome' => 'string',
      'pathImagem' => 'string'
  ];

  protected $table = 'Curso';
  protected $primaryKey = 'cursoId';

  protected $cursoId; // PK Curso
  protected $nome;
  protected $pathImagem;
}
