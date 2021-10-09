<?php

namespace App\Http\Controllers\Auth\Models;

class AppSummarySession {

  public $token;
  public $userId;

  public function __construct($token, $userId)
  {
    $this->token = $token;
    $this->userId = $userId;
  }

}