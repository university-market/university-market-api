<?php

namespace App\Models\Curso;

use \Illuminate\Database\Eloquent\Model;

use App\Models\Estudante\Estudante;
use App\Models\Publicacao\Publicacao;

class Curso extends Model {
  
  // Nao registrar data/hora criacao/alteracao
  public $timestamps = false;

  // protected $table = 'Cursos';
  protected $primaryKey = 'id';

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
      // 'id' => 'integer',
      'nome' => 'string',
      'caminho_imagem' => 'string'
  ];

  protected $id; // PK
  protected $nome;
  protected $caminho_imagem;

  // Entity Relationships

  /**
   * Obtem Estudantes associados ao Curso
   */
  public function estudantes()
  {
    return $this->hasMany(Estudante::class);
  }

  /**
   * Obtem Publicacoes associadas ao Curso
   */
  public function publicacoes()
  {
    return $this->hasMany(Publicacao::class);
  }
}
