<?php

namespace App\Models\Estudante;

use \Illuminate\Database\Eloquent\Model;

class Denuncia extends Model {
  
  // Registrar data/hora criacao/alteracao
  public $timestamps = true;

  /**
   * The attributes that should be cast.
   *
   * @var array
   */

  protected $casts = [
    'id' => 'integer',
    'descricao' => 'string',
    'estudante_id_autor' => 'integer',
    'estudante_id_denunciado' => 'integer',
    'movimentacao_id' => 'integer',
  ];

  protected $table = 'denuncias';
  protected $primaryKey = 'id';

  protected $id; // PK
  protected $descricao;
  protected $apurada;
  protected $dataHoraCriacao;
  protected $dataHoraAtualizacao;
  protected $finished_at;
  protected $estudante_id_autor; // FK Estudante
  protected $estudante_id_denunciado; // F
  protected $movimentacao_id;
}
