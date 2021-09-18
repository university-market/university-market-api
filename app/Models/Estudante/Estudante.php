<?php

namespace App\Models\Estudante;

use \Illuminate\Database\Eloquent\Model;

use App\Models\Curso\Curso;
use App\Models\Instituicao\Instituicao;

class Estudante extends Model {
    
  public $timestamps = false; // Nao registrar data/hora criacao/alteracao

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'estudanteId' => 'integer',
    'nome' => 'string',
    'email' => 'string',
    'telefone' => 'string',
    'dataNascimento' => 'datetime',
    'hashSenha' => 'string',
    'pathFotoPerfil' => 'string',
    'ativo' => 'boolean',
    'dataHoraCadastro' => 'datetime',
    'cursoId' => 'integer',
    'instituicaoId' => 'integer'
  ];

  protected $table = 'Estudante';
  protected $primaryKey = 'estudanteId';

  protected $estudanteId; // PK Estudante
  protected $nome;
  protected $email;
  protected $telefone;
  protected $dataNascimento;
  protected $hashSenha;
  protected $pathFotoPerfil;
  protected $ativo;
  protected $dataHoraCadastro;
  protected $cursoId; // FK Curso
  protected $instituicaoId; // FK Instituicao

  // Entity Relationships

  /**
   * Obtem o Curso associado ao Estudante
   */
  public function curso()
  {
    return $this->hasOne(Curso::class, 'cursoId', 'cursoId');
  }

  /**
   * Obtem a Instituicao associada ao Estudante
   */
  public function instituicao()
  {
    return $this->hasOne(Instituicao::class, 'instituicaoId', 'instituicaoId');
  }
}
