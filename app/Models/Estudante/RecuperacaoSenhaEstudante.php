<?php

namespace App\Models\Estudante;

use \Illuminate\Database\Eloquent\Model;

use App\Models\Estudante\Estudante;

class RecuperacaoSenhaEstudante extends Model {
    
  public $timestamps = false; // Nao registrar data/hora criacao/alteracao

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'recuperacaoSenhaEstudanteId' => 'integer',
    'tokenRecuperacao' => 'string',
    'tempoExpiracao' => 'integer',
    'email' => 'string',
    'dataHoraSolicitacao' => 'datetime',
    'completo' => 'boolean',
    'expirada' => 'boolean',
    'estudanteId' => 'integer'
  ];

  protected $table = 'RecuperacaoSenhaEstudante';
  protected $primaryKey = 'recuperacaoSenhaEstudanteId';

  protected $recuperacaoSenhaEstudanteId; // PK RecuperacaoSenhaEstudante
  protected $tokenRecuperacao;
  protected $tempoExpiracao;
  protected $email;
  protected $dataHoraSolicitacao;
  protected $completo;
  protected $expirada;
  protected $estudanteId; // FK Estudante

  // Entity Relationships

  /**
   * Obtem Estudante associado a Recuperacao de Senha
   */
  public function estudante()
  {
    return $this->hasOne(Estudante::class, 'estudanteId', 'estudanteId');
  }
}
