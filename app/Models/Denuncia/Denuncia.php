<?php

namespace App\Models\Denuncia;

// Base
use App\Base\Models\UniversityMarketModel;
use App\Base\Exceptions\UniversityMarketException;

// Entidades
use App\Models\Denuncia\TipoDenuncia;
use App\Models\Publicacao\Publicacao;
use App\Models\Estudante\Estudante;
use App\Models\Denuncia\SituacaoDenuncia;
use App\Models\Denuncia\ResultadoDenuncia;

class Denuncia extends UniversityMarketModel {
  
  // Nome da entidade no banco de dados
  protected $table = 'Denuncias';

  // Registrar data/hora criacao/alteracao
  public $timestamps = true;

  // Type Casting para campos com tipos especiais (não string)
  protected $casts = [
    'estudante_id_autor' => 'integer',
    'estudante_id_denunciado' => 'integer',
    'publicacao_id' => 'integer',
  ];

  // Primary Key da entidade
  protected $id;
  protected $primaryKey = 'id';
  
  // Properties
  protected $descricao;
  
  // Timestamps da entidade
  private $update_at;
  private $created_at;

  /**
   * @region Entity Relationships
   */

  // Foreign Key para entidade de Estudante
  protected $estudante_id_autor;
  public function estudante_autor()
  {
    return $this->hasOne(Estudante::class, 'id', 'estudante_id_autor');
  }

  // Foreign Key para entidade de Estudante
  protected $estudante_id_denunciado;
  public function estudante_denunciado()
  {
    return $this->hasOne(Estudante::class, 'id', 'estudante_id_denunciado');
  }

  // Foreign Key para entidade de Publicacao
  protected $publicacao_id;
  public function publicacao()
  {
    return $this->hasOne(Publicacao::class, 'id');
  }

  // Foreign Key para entidade de Tipo_Denuncia
  protected $tipo_denuncia_id;
  public function tipo_denuncia()
  {
    return $this->hasOne(TipoDenuncia::class, 'id', 'tipo_denuncia_id');
  }

  // Foreign Key para entidade de Situacao_Denuncia
  protected $situacao_denuncia_id;
  public function situacao_denuncia()
  {
    return $this->hasOne(SituacaoDenuncia::class, 'id', 'situacao_denuncia_id');
  }

  // Foreign Key para entidade de Resultado_Denuncia
  protected $resultado_denuncia_id;
  public function resultado_denuncia()
  {
    return $this->hasOne(ResultadoDenuncia::class, 'id', 'resultado_denuncia_id');
  }
}
