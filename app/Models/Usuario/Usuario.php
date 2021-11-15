<?php

namespace App\Models\Usuario;

// Base
use App\Base\Models\UniversityMarketModel;
use App\Base\Exceptions\UniversityMarketException;

// Common
use App\Common\Constants\UniversityMarketConstants;

// Models
use App\Models\Instituicao\Instituicao;
use App\Models\Base\UniversityMarketActorBase;

class Usuario extends UniversityMarketActorBase
{

  // Nome da entidade no banco de dados
  protected $table = 'Usuarios';

  // Registrar data/hora criacao/alteracao
  public $timestamps = true;

  // Type Casting para campos com tipos especiais (não string)
  protected $casts = [
    'data_nascimento'   => 'date',
    'deleted_at'        => 'datetime'
  ];

  // Properties
  protected $data_nascimento;

  // Timestamps da entidade
  private $created_at;
  private $updated_at;
  protected $deleted_at;

  /**
   * @region Entity Relationships
   */

  // Foreign Key para entidade de Instituicao
  protected $instituicao_id;
  public function instituicao()
  {
    return $this->hasOne(Instituicao::class, 'id', 'instituicao_id');
  }

  /**
   * @region Entity Acessors and Mutators
   */

  // Setter para Email
  public function setEmailAttribute($value)
  {

    $usuario = Usuario::findByEmail($value);

    if (!is_null($usuario))
        throw new UniversityMarketException("Usuário já possui cadastro");

    // Limpar caracteres inválidos do email
    // $email = filter_var($value, FILTER_SANITIZE_EMAIL);

    // Validar formato
    if (!filter_var($value, FILTER_VALIDATE_EMAIL))
      throw new UniversityMarketException("O formato do e-mail $value não é válido");

    $this->attributes['email'] = $value;
  }

  /**
   * @region Queryable methods
   */

  // Buscar Estudante por e-mail (UNIQUE)
  public static function findByEmail($value)
  {

    return Usuario::where('email', $value)->first();
  }
}
