<?php

namespace App\Http\Controllers\Auth\Models;

class AppSummarySession {

  public $token;

  public function __construct($token)
  {
    $this->token = $token;
  }

}