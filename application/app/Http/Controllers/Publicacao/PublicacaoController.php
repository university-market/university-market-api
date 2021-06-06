<?php

namespace App\Http\Controllers\Publicacao;

use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models de publicacao utilizadas
use App\Models\Publicacao;
use App\Http\Controllers\Publicacao\Models\PublicacaoCriacaoModel;
use App\Http\Controllers\Publicacao\Models\PublicacaoDetalheModel;

class PublicacaoController extends UniversityMarketController {

    public function obter($publicacaoId) {

        $condition = [
            'publicacaoId' => $publicacaoId,
            'dataHoraExclusao' => null
        ];

        $publicacao = Publicacao::where($condition)->first();

        if (\is_null($publicacao))
            throw new \Exception("Publicação não encontrada");

        $model = $this->cast($publicacao, PublicacaoDetalheModel::class);

        return response()->json($model);
    }

    public function criar(Request $request) {

        $model = $this->cast($request, PublicacaoCriacaoModel::class);

        // Validar informacoes construidas na model
        $model->validar();

        $publicacao = new Publicacao();

        $publicacao->titulo = $model->titulo;
        $publicacao->descricao = $model->descricao;
        $publicacao->valor = $model->valor;
        $publicacao->pathImagem = $model->pathImagem;
        $publicacao->dataHoraCriacao = \date("Y-m-d H:i:s");

        $publicacao->save();

        $publicacaoId = $publicacao->publicacaoId;

        return response()->json($publicacaoId);
    }

    public function listar() {

        $publicacoes = Publicacao::where('dataHoraExclusao', null)->get()->toArray();

        $model = $this->cast($publicacoes, PublicacaoDetalheModel::class);

        return response()->json($model);
    }

}