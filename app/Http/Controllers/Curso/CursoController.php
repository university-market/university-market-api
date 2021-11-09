<?php

namespace App\Http\Controllers\Curso;

use Illuminate\Http\Request;

// Base
use App\Base\Controllers\UniversityMarketController;
use App\Base\Exceptions\UniversityMarketException;

// Common
use App\Common\Datatype\KeyValuePair;

// Entidades
use App\Models\Curso\Curso;
use App\Models\Instituicao\Instituicao;
use App\Models\Instituicao\Instituicao_Curso;

// Models de curso utilizadas

class CursoController extends UniversityMarketController {

  public function listarPorInstituicao($instituicaoId) {

    if (is_null($instituicaoId))
      throw new UniversityMarketException("Instituição não encontrada");

    $instituicao = Instituicao::find($instituicaoId);

    if (is_null($instituicao))
      throw new UniversityMarketException("Instituição não encontrada");

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