<?php

namespace App\Models\Estudante;

use \Illuminate\Database\Eloquent\Model;

use App\Models\Estudante\Estudante;

class RecuperacaoSenha extends Model {
  
  // Registrar data/hora criacao/alteracao
  public $timestamps = true;

  protected $table = 'Recuperacoes_Senhas';
  // protected $primaryKey = 'id';

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    // 'id' => 'integer',
    'token' => 'string',
    'email' => 'string',
    'completa' => 'boolean',
    'expirada' => 'boolean',
    'expiration_at' => 'integer',
    // 'estudante_id' => 'integer',
    // 'usuario_id' => 'integer'
  ];

  protected $id; // PK
  protected $token;
  protected $email;
  protected $completa;
  protected $expirada;
  protected $expiration_at;
  protected $created_at;
  protected $updated_at;

  protected $estudante_id; // FK Estudante
  // protected $usuario_id; // FK Usuario

  // Entity Relationships

  /**
   * Obtem Estudante associado a Recuperacao de Senha
   */
  public function estudante()
  {
    return $this->hasOne(Estudante::class);
  }

  /**
   * Obtem Estudante associado a Recuperacao de Senha
   */
  // public function usuario()
  // {
  //   return $this->hasOne(Usuario::class);
  // }
}