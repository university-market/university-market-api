<?php

namespace App\Models\Denuncia;

// Base
use App\Base\Models\UniversityMarketModel;

class SituacaoDenuncia extends UniversityMarketModel {
  
  // Nome da entidade no banco de dados
  protected $table = 'Situacoes_Denuncias';

  // Registrar data/hora criacao/alteracao
  public $timestamps = false;

  // Type Casting para campos com tipos especiais (não string)
  protected $casts = [];

  // Primary Key da entidade
  protected $id;
  protected $primaryKey = 'id';

  // Properties
  protected $situacao;
  protected $descricao;
}
