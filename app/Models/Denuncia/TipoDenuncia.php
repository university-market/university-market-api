<?php

namespace App\Models\Denuncia;

// Base
use App\Base\Models\UniversityMarketModel;

class TipoDenuncia extends UniversityMarketModel {
  
  // Nome da entidade no banco de dados
  protected $table = 'Tipos_Denuncias';

  // Registrar data/hora criacao/alteracao
  public $timestamps = false;

  // Type Casting para campos com tipos especiais (não string)
  protected $casts = [];

  // Primary Key da entidade
  protected $id;
  protected $primaryKey = 'id';

  // Properties
  protected $descricao;
}
