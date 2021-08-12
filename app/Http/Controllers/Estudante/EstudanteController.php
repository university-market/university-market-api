<?php

namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models de publicacao utilizadas
use App\Models\Estudante;
use App\Http\Controllers\Estudante\Models\EstudanteDetalheModel;

class EstudanteController extends UniversityMarketController {

  public function obter($estudanteId) {

    $condition = [
      'estudanteId' => $estudanteId,
      'dataHoraExclusao' => null
    ];

    $estudante = Estudante::where($condition)->first();

    if (\is_null($estudante))
        throw new \Exception("Estudante não encontrado");

    $model = $this->cast($estudante, EstudanteDetalheModel::class);

    return response()->json($model);
  }
}