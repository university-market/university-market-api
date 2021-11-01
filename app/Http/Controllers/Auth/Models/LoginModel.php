<?php

namespace App\Http\Controllers\Auth\Models;

use App\Base\Exceptions\UniversityMarketException;

class LoginModel {

  public $email;
  public $senha;

  /**
   * Validar dados iniciais recebidos neste model (logo após type casting)
   * @method validar
   */
  public function validar() {

    if (is_null($this->email) || empty($this->email))
      throw new UniversityMarketException("Um e-mail deve ser informado");

    if (is_null($this->senha) || empty($this->senha))
      throw new UniversityMarketException("A senha é obrigatória");

    return $this;
  }
}