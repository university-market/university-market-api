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

    public function alterar(Request $request, $publicacaoId) {

        $model = $this->cast($request, PublicacaoCriacaoModel::class);

        $condition = [
            'publicacaoId' => $publicacaoId,
            'dataHoraExclusao' => null
        ];
        $publicacao = Publicacao::where($condition)->first();

        if (\is_null($publicacao))
            throw new \Exception("Publicação não encontrada");

        // Validação valor recebido na model
        if ($model->valor !== null && !\is_numeric($model->valor))
            throw new \Exception("O valor informado não é válido");

        $publicacao->titulo = (\is_null($model->titulo) || empty(trim($model->titulo))) ? 
            $publicacao->titulo : trim($model->titulo);
        $publicacao->descricao = (\is_null($model->descricao) || empty(trim($model->descricao))) ? 
            $publicacao->descricao : trim($model->descricao);
        $publicacao->valor = (\is_null($model->valor) || empty(trim($model->valor))) ? 
            $publicacao->valor : (double)$model->valor;
        $publicacao->pathImagem = (\is_null($model->pathImagem) || empty(trim($model->pathImagem))) ? 
            $publicacao->pathImagem : trim($model->pathImagem);

        $publicacao->save();

        return response(null, 200);
    }

}