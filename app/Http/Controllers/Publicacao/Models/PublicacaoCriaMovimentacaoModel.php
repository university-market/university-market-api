<?php

namespace App\Http\Controllers\Publicacao\Models;

// Base
use App\Base\Exceptions\UniversityMarketException;

class PublicacaoCriaMovimentacaoModel {

    public $publicacaoId;
    public $titulo;
    public $descricao;
    public $valor;
    public $especificacoesTecnicas;
    public $pathImagem;
    public $dataHoraCriacao;
    public $vendida;

}