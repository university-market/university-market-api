<?php

namespace App\Http\Controllers\Curso;

use App\Common\Datatype\KeyValuePair;
use App\Exceptions\Base\UMException;
use App\Http\Controllers\Base\UniversityMarketController;

// Models de publicacao utilizadas
use App\Models\Instituicao\Instituicao_Curso;
use App\Models\Curso\Curso;
use App\Models\Instituicao\Instituicao;

class CursoController extends UniversityMarketController {

  public function listarPorInstituicao($instituicaoId) {

    if (is_null($instituicaoId))
        throw new UMException("Instituição não encontrada");

    $instituicao = Instituicao::find($instituicaoId);

    if (is_null($instituicao))
      throw new UMException("Instituição não encontrada");

    $relations = Instituicao_Curso::with(['curso'])->where('instituicao_id', $instituicaoId)->get();

    $list = [];
    foreach ($relations as $relation) {

      $model = new KeyValuePair();

      $model->key = $relation->curso->id;
      $model->value = $relation->curso->nome;

      $list[] = $model;
    }

    return $this->response($list);
  }

  public function listarTodos() {

    $cursos = Curso::all()->getDictionary();

    $list = [];

    // Construir listagem de models apenas com informações necessárias
    foreach ($cursos as $curso) {

      $model = new KeyValuePair();

      $model->key = $curso->id;
      $model->value = $curso->nome;

      $list[] = $model;
    }

    return $this->response($list);
  }
}