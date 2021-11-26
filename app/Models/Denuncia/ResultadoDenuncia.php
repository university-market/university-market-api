<?php

namespace App\Models\Denuncia;

// Base
use App\Base\Models\UniversityMarketModel;
use App\Base\Exceptions\UniversityMarketException;

class ResultadoDenuncia extends UniversityMarketModel {
  
  // Nome da entidade no banco de dados
  protected $table = 'Resultados_Denuncias';

  // Registrar data/hora criacao/alteracao
  public $timestamps = false;

  // Type Casting para campos com tipos especiais (não string)
  protected $casts = [];

  // Primary Key da entidade
  protected $id;
  protected $primaryKey = 'id';
  
  // Properties
  protected $resultado;

  /**
   * @region Entity Relationships
   */
}