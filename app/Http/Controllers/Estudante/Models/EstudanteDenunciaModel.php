<?php

namespace App\Http\Controllers\Estudante\Models;

use App\Base\Exceptions\UniversityMarketException;

class EstudanteDenunciaModel {

  public $estudante_id_autor;
  public $estudante_id_denunciado;
  public $movimentacao_id;
  public $motivo;
  public $descricao;

  public function validar() {

    if ($this->estudante_id_denunciado === $this->estudante_id_autor)
      throw new UniversityMarketException("Estudante não pode se denunciar");
  }

}