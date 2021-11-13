<?php

namespace App\Models\Estudante;

// Base
use App\Base\Models\UniversityMarketModel;
use App\Base\Exceptions\UniversityMarketException;

// Common
use Illuminate\Support\Facades\Hash;
use App\Common\Constants\UniversityMarketConstants;

// Models
use App\Models\Curso\Curso;
use App\Models\Publicacao\Publicacao;
use App\Models\Instituicao\Instituicao;

class Estudante extends UniversityMarketModel {
  
  // Nome da entidade no banco de dados
  protected $table = 'Estudantes';

  // Registrar data/hora criacao/alteracao
  public $timestamps = true;

  // Type Casting para campos com tipos especiais (não string)
  protected $casts = [
    'ativo'             => 'boolean',
    'data_nascimento'   => 'date',
    'deleted_at'        => 'datetime'
  ];

  // Primary key da entidade
  protected $id;
  protected $primaryKey = 'id';

  // Properties
  protected $nome;
  protected $email;
  protected $senha;
  protected $ativo;
  protected $caminho_foto_perfil;
  protected $data_nascimento;

  // Timestamps da entidade
  private $created_at;
  private $updated_at;
  protected $deleted_at;

  /**
   * @region Entity Relationships
   */

  // Foreign Key para entidade de Curso
  protected $curso_id;
  public function curso()
  {
    return $this->hasOne(Curso::class, 'id', 'curso_id');
  }

  // Foreign Key para entidade de Instituicao
  protected $instituicao_id;
  public function instituicao()
  {
    return $this->hasOne(Instituicao::class, 'id', 'instituicao_id');
  }

  // Relacionamento Estudante com Publicacao
  public function publicacoes()
  {
    return $this->hasMany(Publicacao::class, 'estudante_id', 'id');
  }

  /**
   * @region Entity Acessors and Mutators
   */
  
  // Setter para Nome
  public function setNomeAttribute($value) {

    $counter = explode(' ', $value ?? '');

    // Ao menos um nome informado
    if (count($counter) <= 1)
      throw new UniversityMarketException("Um nome completo deve ser fornecido para o estudante");

    $this->attributes['nome'] = $value;
  }

  // Setter para Email
  public function setEmailAttribute($value) {

    $estudante = Estudante::findByEmail($value);

    if (!is_null($estudante)) {
      
      // Incluir instituicao de ensino na query para exibir alerta com razao social
      $estudante->with(['instituicao'])->first();
      throw new UniversityMarketException("Estudante já possui cadastro em {$estudante->instituicao->razao_social}");
    }

    // Limpar caracteres inválidos do email
    // $email = filter_var($value, FILTER_SANITIZE_EMAIL);

    // Validar formato
    if (!filter_var($value, FILTER_VALIDATE_EMAIL))
      throw new UniversityMarketException("O formato do e-mail $value não é válido");

    $this->attributes['email'] = $value;
  }

  // Setter para Senha
  public function setSenhaAttribute($value) {

    $password_config = UniversityMarketConstants::password();

    if (is_null($value))
      throw new UniversityMarketException("A senha é obrigatória");

    if (strlen($value) < $password_config['min_length'])
      throw new UniversityMarketException("A senha deve conter ao menos {$password_config['min_length']} caracteres");

    $this->attributes['senha'] = Hash::make($value);
  }

  /**
   * @region Queryable methods
   */
  public static function findByEmail($value) {

    return Estudante::where('email', $value)->first();
  }
  
}
