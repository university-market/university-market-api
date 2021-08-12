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
      'nome' => 'string',
      'ra' => 'string',
      'email' => 'string',
      'telefone' => 'string',
      'dataNascimento' => 'datetime',
      'hashSenha' => 'string',
      'pathFotoPerfil' => 'string',
      'ativo' => 'boolean',
      'blocked' => 'boolean',
      'dataHoraFimBlock' => 'datetime',
      'dataHoraCadastro' => 'datetime',
      'dataHoraExclusao' => 'datetime',
      'cursoId' => 'integer',
      'instituicaoId' => 'integer'
  ];

  protected $table = 'Estudante';
  protected $primaryKey = 'estudanteId';

  protected $estudanteId; // PK Estudante
  protected $nome;
  protected $ra;
  protected $email;
  protected $telefone;
  protected $dataNascimento;
  protected $hashSenha;
  protected $pathFotoPerfil;
  protected $ativo;
  protected $blocked;
  protected $dataHoraFimBlock;
  protected $dataHoraCadastro;
  protected $dataHoraExclusao;
  protected $cursoId; // FK Curso
  protected $instituicaoId; // FK Instituicao
}
