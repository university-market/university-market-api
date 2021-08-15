<?php

namespace App\Http\Controllers\Auth\Models;

use Exception;

class LoginModel {

  public $email;
  public $senha;

  public function validar() {

    if (is_null($this->email) || empty($this->email))
      throw new \Exception("Um e-mail deve ser informado");

    if (is_null($this->senha) || empty($this->senha))
      throw new \Exception("A senha é obrigatória");
  }
}