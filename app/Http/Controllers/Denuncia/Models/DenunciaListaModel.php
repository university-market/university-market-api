<?php

namespace App\Http\Controllers\Denuncia\Models;

class DenunciaListaModel {

    public $denunciaId;
    
    public $descricao;
    public $apurada;

    public $tipoDenuncia;
    public $publicacaoId;

    public $dataHoraCriacao;
    public $dataHoraUltimaRevisao;

    public $estudanteAutor;
    public $estudanteAutorId;

    public $estudanteDenunciado;
    public $estudanteDenunciadoId;
}