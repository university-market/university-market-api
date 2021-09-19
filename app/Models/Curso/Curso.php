<?php

namespace App\Models\Curso;

use \Illuminate\Database\Eloquent\Model;

use App\Models\Estudante\Estudante;
use App\Models\Publicacao\Publicacao;

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

  // Entity Relationships

  /**
   * Obtem Estudantes associados ao Curso
   */
  public function estudante()
  {
    return $this->hasMany(Estudante::class, 'estudanteId');
  }

  /**
   * Obtem Publicacao associadas ao Curso
   */
  public function publicacao()
  {
    return $this->hasMany(Publicacao::class, 'publicacaoId');
  }
}
