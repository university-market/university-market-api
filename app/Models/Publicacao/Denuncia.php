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
    'descricao' => 'string',
    'estudante_id_autor' => 'integer',
    'estudante_id_denunciado' => 'integer',
    'publicacao_id' => 'integer',
  ];

  protected $table = 'denuncias';
  protected $primaryKey = 'id';

  protected $id; // PK
  protected $descricao;
  protected $apurada;
  protected $estudante_id_autor; // FK Estudante
  protected $estudante_id_denunciado; // FK Estudante
  protected $publicacao_id; // FK publicação
  protected $tipo_denuncia_id; // FK Tipos_Denuncias
  
  private $approved_at;
  private $update_at;
  private $created_at;
}
