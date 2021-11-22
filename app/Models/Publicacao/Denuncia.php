<?php

namespace App\Models\Publicacao;

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
    'motivo' => 'string',
    'estudante_id_autor' => 'integer',
    'publicacao_id' => 'integer',
  ];

  protected $table = 'denuncias_publicacoes';
  protected $primaryKey = 'id';

  protected $id; // PK
  protected $motivo;
  protected $apurada;
  private $approved_at;
  protected $dataHoraCriacao;
  protected $dataHoraAtualizacao;
  protected $estudante_id_autor; // FK Estudante
  protected $publicacao_id; // FK publicação
}
