<?php

namespace App\Http\Controllers\Instituicao\Models;

class InstituicaoCriacaoModel {

  public $nomeFantasia;
  public $razaoSocial;
  public $cnpj;
  public $email;
  public $telefone;

  public function validar() {
      
    if (is_null($this->razaoSocial) || empty(trim($this->razaoSocial)))
      throw new \Exception("A razão social é obrigatória");
    
    if (is_null($this->cnpj) || empty(trim($this->cnpj)))
      throw new \Exception("O CNPJ da instituição é obrigatório");

    if (
      is_null($this->email) || empty(trim($this->email)) &&
      is_null($this->telefone) || empty(trim($this->telefone))
    )
      throw new \Exception("Ao menos um e-mail ou telefone deve ser informado");
  }
}