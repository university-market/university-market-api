<?php

namespace App\Http\Controllers\Auth\Models;

class AppSummarySession {

  public $token;
  public $userId;
  public $nome;

  public function __construct($token, $userId, $nome)
  {
    $this->token = $token;
    $this->userId = $userId;
    $this->nome = $nome;
  }

}