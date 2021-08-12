<?php

namespace App\Http\Controllers\Instituicao\Models;

class InstituicaoCriacaoModel {

  public $nomeFantasia;
  public $razaoSocial;
  public $cnpj;
  public $cpfRepresentante;
  public $emailContato;

  public function validar() {
      
    if (is_null($this->razaoSocial) || empty(trim($this->razaoSocial)))
      throw new \Exception("A razão social é obrigatória");
    
    if (is_null($this->cnpj) || empty(trim($this->cnpj)))
      throw new \Exception("O CNPJ da instituição é obrigatório");

    if (is_null($this->cpfRepresentante) || empty(trim($this->cpfRepresentante)))
      throw new \Exception("O CPF do representante é obrigatório");

    if (\is_null($this->emailContato) || empty(trim($this->emailContato)))
      throw new \Exception("Um e-mail para contato deve ser fornecido");
  }
}