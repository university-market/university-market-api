<?php

namespace App\Models\Estudante;

use \Illuminate\Database\Eloquent\Model;

class Contato extends Model {
    
  // Registrar data/hora criacao/alteracao
  public $timestamps = true;

  // protected $table = 'Contatos';
  // protected $primaryKey = 'id';

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    // 'id' => 'integer',
    'conteudo' => 'string',
    'deleted' => 'boolean',
    // 'tipo_contato_id' => 'integer',
    // 'estudante_id' => 'integer'
  ];

  protected $id; // PK
  protected $conteudo;
  protected $deleted;
  private $created_at;
  private $updated_at;
  

  /**
   * @region Entity Relationships
   */

  // Foreign Key para entidade de Tipo_Contato
  protected $tipo_contato_id;
  public function tipo_contato()
  {
    return $this->hasOne(TipoContato::class);
  }

  // Foreign Key para entidade de Estudante
  protected $estudante_id;
  public function estudante()
  {
    return $this->hasOne(Estudante::class);
  }
  
  /**
   * @region Entity Acessors and Mutators
   */
  

}
