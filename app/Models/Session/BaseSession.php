<?php

namespace App\Models\Session;

use Illuminate\Database\Eloquent\Model;

class BaseSession extends Model {

  // Registrar data/hora criacao/alteracao
  public $timestamps = true;

  // Primary Key da entidade
  protected $id;
  protected $primaryKey = 'id';

  // Type Casting para propriedades com valores especiais (não-string)
  protected $casts = [
    'token' => 'string',
    'expiration_time' => 'integer'
  ];

  // Columns
  protected $token;
  protected $expiration_time;

  // Timestamps
  private $created_at;

}