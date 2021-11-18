<?php

namespace App\Http\Controllers\Publicacao\Models;

use App\Base\Exceptions\UniversityMarketException;

class PublicacaoDenunciaModel {

  public $estudante_id_autor;
  public $estudante_id_denunciado;
  public $publicacao_id;
  public $motivo;
  public $tipo_denuncia_id;

}