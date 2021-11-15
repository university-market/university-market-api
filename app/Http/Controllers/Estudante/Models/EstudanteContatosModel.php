<?php

namespace App\Http\Controllers\Estudante\Models;

class EstudanteContatosModel {
  public $id;
  public $conteudo;
  public $tipo_contato_id;
  public $estudante_id;

  public function validar() {

    if (is_null($this->conteudo) || empty(trim($this->conteudo)))
      throw new \Exception("O conteúdo é obrigatório");

    if (is_null($this->tipo_contato_id) || empty(trim($this->tipo_contato_id)))
      throw new \Exception("O tipo de contato é obrigatório");

  }
}

