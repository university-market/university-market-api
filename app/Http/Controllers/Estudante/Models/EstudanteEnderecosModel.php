<?php

namespace App\Http\Controllers\Estudante\Models;

class EstudanteEnderecosModel
{

  public $id;
  public $estudanteId;
  public $rua;
  public $numero;
  public $cep;
  public $complemento;
  public $municipio;
  public $bairro;

  public function validar()
  {

    if (is_null($this->rua) || empty(trim($this->rua)))
      throw new \Exception("O logradouro é obrigatório");

    if (is_null($this->numero) || empty(trim($this->numero)))
      throw new \Exception("O número é obrigatório");

    if (is_null($this->cep) || empty(trim($this->cep)))
      throw new \Exception("O cep é obrigatório");

    if (is_null($this->municipio) || empty(trim($this->municipio)))
      throw new \Exception("O município é obrigatório");

    if (is_null($this->bairro) || empty(trim($this->bairro)))
      throw new \Exception("O bairro é obrigatório");
  }
}
