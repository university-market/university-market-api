<?php

namespace App\Http\Controllers\Publicacao;

use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models de publicacao utilizadas
use App\Models\Publicacao;
use App\Http\Controllers\Publicacao\Models\PublicacaoCriacaoModel;

class PublicacaoController extends UniversityMarketController {

    public function criar(Request $request) {

        $model = $this->makeModel($request, PublicacaoCriacaoModel::class);

        // Validar informacoes construidas na model
        $model->validar();

        $publicacao = new Publicacao();

        $publicacao->titulo = $model->titulo;
        $publicacao->descricao = $model->descricao;
        $publicacao->valor = $model->valor;
        $publicacao->pathImagem = $model->pathImagem;
        $publicacao->dataHoraCriacao = \date("Y-m-d H:i:s");

        $publicacao->save();

        return response()->json($publicacao->publicacaoId);
    }

}