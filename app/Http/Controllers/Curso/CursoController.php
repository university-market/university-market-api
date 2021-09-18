<?php

namespace App\Http\Controllers\Curso;

use App\Exceptions\Base\UMException;
use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Models de publicacao utilizadas
use App\Models\Instituicao\Instituicao_Curso;
use App\Http\Controllers\Curso\Models\CursoListaModel;
use App\Models\Instituicao\Instituicao;

class CursoController extends UniversityMarketController {

  public function listarPorInstituicao($instituicaoId) {

    if (is_null($instituicaoId))
        throw new UMException("Instituição não encontrada");

    $instituicao = Instituicao::find($instituicaoId);

    if (is_null($instituicao))
        throw new UMException("Instituição não encontrada");

    $cursos = Instituicao_Curso::with('curso')->where('instituicaoId', $instituicaoId)->get();

    $list = [];

    // Construir listagem de models apenas com informações necessárias
    foreach ($cursos as $curso) {

        $model = new CursoListaModel();

        $model->cursoId = $curso->cursoId;
        $model->nome = $curso->curso->nome;

        $list[] = $model;
    }

    return response()->json($list);
  }
}