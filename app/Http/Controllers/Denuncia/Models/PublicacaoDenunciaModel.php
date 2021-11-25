<?php

namespace App\Http\Controllers\Denuncia\Models;

use App\Base\Exceptions\UniversityMarketException;

class PublicacaoDenunciaModel {

  public $estudante_id_autor;
  public $estudante_id_denunciado;
  public $publicacao_id;
  public $motivo;
  public $tipo_denuncia_id;

  public function validar() {

    if (is_null($this->motivo) || empty($this->motivo))
        throw new UniversityMarketException("O motivo da denúncia é obrigatório");
  }
}