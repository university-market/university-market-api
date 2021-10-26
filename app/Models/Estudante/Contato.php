<?php

namespace App\Models\Estudante;

use \Illuminate\Database\Eloquent\Model;

class Contato extends Model {
    
  public $timestamps = true; // Registrar data/hora criacao/alteracao

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'conteudo' => 'string',
    'deleted' => 'boolean',
    'tipo_contato_id' => 'integer',
    'estudante_id' => 'integer'
  ];

  protected $table = 'Contatos';
  protected $primaryKey = 'id';

  protected $id; // PK Contatos
  protected $conteudo;
  protected $deleted;
  protected $dataHoraCriacao;
  protected $dataHoraAtualizacao;
  protected $tipo_contato_id; // FK tipos_contatos
  protected $estudante_id; // FK estudante

}
