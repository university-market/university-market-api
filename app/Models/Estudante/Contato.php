<?php

namespace App\Models\Estudante;

use \Illuminate\Database\Eloquent\Model;

class Contato extends Model {
  
  // Nome da entidade no banco de dados
  protected $table = 'Contatos';

  // Registrar data/hora criacao/alteracao
  public $timestamps = true;

  // Type Casting para campos com tipos especiais (não string)
  protected $casts = [
    'conteudo' => 'string',
    'deleted' => 'boolean'
  ];

  // Primary Key da entidade
  protected $id;
  protected $primaryKey = 'id';

  // Properties
  protected $conteudo;
  protected $deleted;

  // Timestamps da entidade
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
