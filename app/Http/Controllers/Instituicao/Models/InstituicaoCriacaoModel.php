<?php

namespace App\Http\Controllers\Instituicao\Models;

// Base
use App\Base\Exceptions\UniversityMarketException;

class InstituicaoCriacaoModel {

  public $nomeFantasia;
  public $razaoSocial;
  public $cnpj;
  public $email;
  public $telefone;

  public function validar() {
      
    if (is_null($this->razaoSocial) || empty(trim($this->razaoSocial)))
      throw new UniversityMarketException("A razão social é obrigatória");
    
    if (is_null($this->cnpj) || empty(trim($this->cnpj)))
      throw new UniversityMarketException("O CNPJ da instituição é obrigatório");

    if (
      is_null($this->email) || empty(trim($this->email)) &&
      is_null($this->telefone) || empty(trim($this->telefone))
    )
      throw new UniversityMarketException("Ao menos um e-mail ou telefone deve ser informado");
  }
}