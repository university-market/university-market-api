<?php

namespace App\Http\Controllers\Publicacao;

use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Use models de publicacao
use App\Http\Controllers\Publicacao\Models\PublicacaoCriacaoModel;

class PublicacaoController extends UniversityMarketController {

    public function criar(Request $request) {

        $criacaoModel = $this->makeModel($request, PublicacaoCriacaoModel::class);

        $criacaoModel->dataHoraCriacao = \date('Y-m-d H:i:s');
        $criacaoModel->isExcluido = false;

        return response()->json($criacaoModel);
    }

}