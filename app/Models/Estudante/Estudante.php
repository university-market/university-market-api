<?php

namespace App\Models\Estudante;

use \Illuminate\Database\Eloquent\Model;

use App\Models\Curso\Curso;
use App\Models\Instituicao\Instituicao;
use App\Models\Publicacao\Publicacao;

class Estudante extends Model {
  
  // Registrar data/hora criacao/alteracao
  public $timestamps = true;

  // protected $table = 'Estudantes';
  // protected $primaryKey = 'id';

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    // 'id' => 'integer',
    'nome' => 'string',
    'email' => 'string',
    'senha' => 'string',
    'ativo' => 'boolean',
    'caminho_foto_perfil' => 'string',
    'data_nascimento' => 'date',
    'deleted_at' => 'datetime',
    // 'curso_id' => 'integer',
    // 'instituicao_id' => 'integer'
  ];

  protected $id; // PK
  protected $nome;
  protected $email;
  protected $senha;
  protected $ativo;
  protected $caminho_foto_perfil;
  protected $data_nascimento;
  protected $deleted_at;
  protected $created_at;
  protected $updated_at;

  protected $curso_id; // FK Curso
  protected $instituicao_id; // FK Instituicao

  // Entity Relationships

  /**
   * Obtem o Curso associado ao Estudante
   */
  public function curso()
  {
    return $this->hasOne(Curso::class);
  }

  /**
   * Obtem a Instituicao associada ao Estudante
   */
  public function instituicao()
  {
    return $this->hasOne(Instituicao::class);
  }

  /**
   * Obtem as Publicacoes associadas ao Estudante
   */
  public function publicacoes()
  {
    return $this->hasMany(Publicacao::class);
  }
}
