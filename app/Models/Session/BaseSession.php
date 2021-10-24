<?php

namespace App\Models\Session;

use Illuminate\Database\Eloquent\Model;

class BaseSession extends Model {

  // Registrar data/hora criacao/alteracao
  public $timestamps = true;

  // protected $primaryKey = 'id';

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    // 'id' => 'integer',
    'token' => 'string',
    'expiration_time' => 'integer'
  ];

  protected $id; // PK
  protected $token;
  protected $expiration_time;

}