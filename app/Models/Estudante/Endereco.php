<?php

namespace App\Models\Estudante;

use \Illuminate\Database\Eloquent\Model;

class Endereco extends Model {
    
  // Registrar data/hora criacao/alteracao
  public $timestamps = true;

  // protected $table = 'Enderecos';
  // protected $primaryKey = 'id';

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    // 'id' => 'integer',
    'cep' => 'string',
    'logradouro' => 'string',
    'numero' => 'string',
    'municipio' => 'string',
    'bairro' => 'string',
    'complemento' => 'string',
    'atual' => 'boolean',
    // 'tipo_contato_id' => 'integer',
    //'estudante_id' => 'integer'
  ];

  protected $id; // PK
  protected $cep;
  protected $logradouro;
  protected $numero;
  protected $complemento;
  protected $municipio;
  protected $bairro;
  protected $ponto_referencia;
  protected $atual;
  protected $deteted_at;
  private $created_at;
  private $updated_at;

  protected $instituicao_id; // FK Instituicoes
  protected $estudante_id; // FK Estudante

}
