<?php

namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Models de publicacao utilizadas
use App\Models\Estudante;
use App\Http\Controllers\Estudante\Models\EstudanteDetalheModel;
use App\Http\Controllers\Estudante\Models\EstudanteCriacaoModel;

class EstudanteController extends UniversityMarketController {

  public function obter($estudanteId) {

    if (!$estudanteId)
      throw new \Exception("Estudante não encontrado");

    $session = $this->getSession();

    if (!$session)
      throw new \Exception("Sem permissão para realizar esta ação");

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

  public function criar(Request $request) {

    $model = $this->cast($request, EstudanteCriacaoModel::class);

    $model->validar();

    if ($this->estudanteExistente($model->email, $model->ra, $model->instituicaoId))
      throw new \Exception("Estudante já cadastrado nesta instituição de ensino");

    $estudante = new Estudante();

    $estudante->nome = $model->nome;
    $estudante->ra = $model->ra;
    $estudante->email = $model->email;
    $estudante->telefone = $model->telefone;
    $estudante->dataNascimento = $model->dataNascimento;
    $estudante->hashSenha = Hash::make($model->senha);
    $estudante->pathFotoPerfil = null;
    $estudante->ativo = true;
    $estudante->blocked = false;
    $estudante->dataHoraCadastro = date($this->dateTimeFormat);
    $estudante->cursoId = $model->cursoId;
    $estudante->instituicaoId = $model->instituicaoId;
    
    $estudante->save();
  }

  /**
   * @param string $email E-mail do estudante (deve ser único na instituicao)
   * @param string $ra Registro Academico do estudante (deve ser único na instituicao)
   * @param string $instituicaoId Id da intituicao de ensino
   */
  private function estudanteExistente($email, $ra, $instituicaoId) {

    $any = Estudante::where('instituicaoId', $instituicaoId)
      ->where(
        function($query) use ($email, $ra) {

          $query->where('email', $email)
            ->orWhere('ra', $ra);
        }
      )->first();

    return !is_null($any);
  }
}