<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models de conta utilizadas
use App\Http\Controllers\Instituicao\Models\InstituicaoCriacaoModel;

class InstituicaoController extends UniversityMarketController {

  public function cadastrar(Request $request) {

    $model = $this->cast($request, InstituicaoCriacaoModel::class);

    $model->validar();

    return response()->json($model);
  }

}