<?php

namespace App\Models\Account;

// Base
use App\Base\Models\UniversityMarketModel;

// Models
use App\Models\Estudante\Estudante;

class RecuperacaoSenha extends UniversityMarketModel {
  
  // Nome da entidade no banco de dados
  protected $table = 'Recuperacoes_Senhas';

  // Registrar data/hora criacao/alteracao
  public $timestamps = true;

  // Type Casting para campos com tipos especiais (não string)
  protected $casts = [
    'completa' => 'boolean',
    'expirada' => 'boolean',
    'expiration_at' => 'integer'
  ];

  // Primary Key da entidade
  protected $id;
  protected $primaryKey = 'id';

  // Properties
  protected $token;
  protected $email;
  protected $completa;
  protected $expirada;

  // Timestamps
  private $created_at;
  private $updated_at;
  protected $expiration_at;

  /**
   * @region Entity Relationships
   */

  // Foreign Key para entidade de Estudante
  protected $estudante_id;
  public function estudante()
  {
    return $this->hasOne(Estudante::class, 'id');
  }

  // Foreign Key para entidade de Usuario
  // protected $usuario_id;
  // public function usuario()
  // {
  //   return $this->hasOne(Usuario::class);
  // }

  /**
   * @region Entity Acessors and Mutators
   */

  /**
   * @region Queryable methods
   */

  // FindPendenteByToken()

  // FindPendenteByEmail()

}
