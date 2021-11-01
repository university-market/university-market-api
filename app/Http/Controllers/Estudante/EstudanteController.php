<?php

namespace App\Http\Controllers\Estudante;

use Illuminate\Http\Request;

// Base
use App\Base\Controllers\UniversityMarketController;
use App\Base\Exceptions\UniversityMarketException;

// Common
use Illuminate\Support\Facades\Hash;

// Entidades
use App\Models\Estudante\Estudante;

// Models de estudante utilizadas
use App\Http\Controllers\Estudante\Models\EstudanteDetalheModel;
use App\Http\Controllers\Estudante\Models\EstudanteCriacaoModel;

class EstudanteController extends UniversityMarketController {

  public function obter($estudanteId) {

    if (!$estudanteId)
      throw new UniversityMarketException("Estudante não encontrado");

    $session = $this->getSession();
    
    if (is_null($session))
      return $this->unauthorized();

    $estudante = Estudante::find($estudanteId);

    if (\is_null($estudante))
      throw new UniversityMarketException("Estudante não encontrado");

    // Construir model de detalhes do estudante
    $model = new EstudanteDetalheModel();

    $model->estudanteId = $estudante->id;
    $model->nome = $estudante->nome;
    $model->email = $estudante->email;
    $model->dataNascimento = $estudante->data_nascimento;
    $model->pathFotoPerfil = $estudante->caminho_foto_perfil;
    $model->cursoNome = $estudante->curso->nome;
    $model->instituicaoRazaoSocial = $estudante->instituicao->razao_social;

    return $this->response($model);
  }

  public function criar(Request $request) {

    $model = $this->cast($request, EstudanteCriacaoModel::class);

    $model->validar();

    $existente = $this->estudanteExistente($model->email);

    if ($existente !== false) {

      throw new \Exception("Estudante já possui cadastro em $existente");
    }

    $estudante = new Estudante();

    $estudante->nome = $model->nome;
    $estudante->email = $model->email;
    $estudante->senha = Hash::make($model->senha);
    $estudante->ativo = true;
    $estudante->caminho_foto_perfil = null;
    $estudante->data_nascimento = $model->dataNascimento;
    $estudante->curso_id = $model->cursoId;
    $estudante->instituicao_id = $model->instituicaoId;
    
    $estudante->save();
  }

  /**
   * @param string $email E-mail do estudante (deve ser único na instituicao)
   * @param string $instituicaoId Id da intituicao de ensino
   */
  private function estudanteExistente($email) {

    $estudante = Estudante::with('instituicao')->where('email', $email)->first();

    if (is_null($estudante))
      return false;

    return $estudante->instituicao->razao_social;
  }
}