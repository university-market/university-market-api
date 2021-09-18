<?php

namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Models de publicacao utilizadas
use App\Models\Estudante\Estudante;
use App\Http\Controllers\Estudante\Models\EstudanteDetalheModel;
use App\Http\Controllers\Estudante\Models\EstudanteCriacaoModel;

class EstudanteController extends UniversityMarketController {

  public function obter($estudanteId) {

    if (!$estudanteId)
      throw new \Exception("Estudante não encontrado");

    $session = $this->getSession();
    
    if (!$session)
      return $this->unauthorized();

    $estudante = Estudante::find($estudanteId);

    if (\is_null($estudante))
      throw new \Exception("Estudante não encontrado");

    // Construir model de detalhes do estudante
    $model = new EstudanteDetalheModel();

    $model->estudanteId = $estudante->estudanteId;
    $model->nome = $estudante->nome;
    $model->email = $estudante->email;
    $model->telefone = $estudante->telefone;
    $model->dataNascimento = $estudante->dataNascimento;
    $model->pathFotoPerfil = $estudante->pathFotoPerfil;
    $model->cursoNome = $estudante->curso->nome;
    $model->instituicaoRazaoSocial = $estudante->instituicao->razaoSocial;

    return response()->json($model);
  }

  public function criar(Request $request) {

    $model = $this->cast($request, EstudanteCriacaoModel::class);

    $model->validar();

    if ($this->estudanteExistente($model->email, $model->instituicaoId))
      throw new \Exception("Estudante já cadastrado nesta instituição de ensino");

    $estudante = new Estudante();

    $estudante->nome = $model->nome;
    $estudante->email = $model->email;
    $estudante->telefone = $model->telefone;
    $estudante->dataNascimento = $model->dataNascimento;
    $estudante->hashSenha = Hash::make($model->senha);
    $estudante->pathFotoPerfil = null;
    $estudante->ativo = true;
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
  private function estudanteExistente($email, $instituicaoId) {

    $any = Estudante::where('instituicaoId', $instituicaoId)
      ->where(
        function($query) use ($email) {

          $query->orWhere('email', $email);
        }
      )->first();

    // $any = Estudante::where('instituicaoId', $instituicaoId)
    //   ->where(
    //     function($query) use ($email, $ra) {

    //       $query->where('email', $email)
    //         ->orWhere('ra', $ra);
    //     }
    //   )->first();

    return !is_null($any);
  }
}