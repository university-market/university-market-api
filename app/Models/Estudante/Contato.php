<?php

namespace App\Models\Estudante;

use \Illuminate\Database\Eloquent\Model;

class Contato extends Model {
<<<<<<< HEAD
  
  // Registrar data/hora criacao/alteracao
  public $timestamps = true;

  // protected $table = 'Contatos';
  // protected $primaryKey = 'id';
=======
    
  public $timestamps = true; // Registrar data/hora criacao/alteracao
>>>>>>> feature-meus-dados

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
<<<<<<< HEAD
    // 'id' => 'integer',
    'conteudo' => 'string',
    'deleted' => 'boolean',
    // 'tipo_contato_id' => 'integer',
    // 'estudante_id' => 'integer'
  ];

  protected $id; // PK
  protected $conteudo;
  protected $deleted;
  protected $created_at;
  protected $updated_at;

//   protected $tipo_contato_id; // FK Tipo Contato
  protected $estudante_id; // FK Estudante

  // Entity Relationships

  /**
   * Obtem o Tipo Contato associado ao Contato
   */
//   public function tipo_contato()
//   {
//     return $this->hasOne(TipoContato::class);
//   }

  /**
   * Obtem o Estudante associado ao Contato
   */
  public function estudante()
  {
    return $this->hasOne(Estudante::class);
  }
=======
    'id' => 'integer',
    'conteudo' => 'string',
    'deleted' => 'boolean',
    'tipo_contato_id' => 'integer',
    'estudante_id' => 'integer'
  ];

  protected $table = 'Contatos';
  protected $primaryKey = 'id';

  protected $id; // PK Contatos
  protected $conteudo;
  protected $deleted;
  protected $dataHoraCriacao;
  protected $dataHoraAtualizacao;
  protected $tipo_contato_id; // FK tipos_contatos
  protected $estudante_id; // FK estudante

>>>>>>> feature-meus-dados
}
