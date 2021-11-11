<?php

namespace App\Http\Controllers\Estudante;

use Illuminate\Http\Request;

// Base
use App\Base\Controllers\UniversityMarketController;
use App\Base\Exceptions\UniversityMarketException;
use App\Base\Logs\Logger\UniversityMarketLogger;
use App\Base\Logs\Type\StdLogType;
use App\Base\Resource\UniversityMarketResource;
// Common
use Illuminate\Support\Facades\Hash;

// Entidades
use App\Models\Estudante\Estudante;

// Models de estudante utilizadas
use App\Models\Estudante\Bloqueios;
use App\Models\Estudante\Contato;
use App\Http\Controllers\Estudante\Models\EstudanteBloqueioModel;
use App\Http\Controllers\Estudante\Models\EstudanteDetalheModel;
use App\Http\Controllers\Estudante\Models\EstudanteCriacaoModel;
use App\Http\Controllers\Estudante\Models\EstudanteDadosModel;
use App\Http\Controllers\Estudante\Models\EstudanteContatosModel;

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

  public function obterDados($estudanteId) {

    if (!$estudanteId)
      throw new UniversityMarketException("Estudante não encontrado");

    $session = $this->getSession();
    
    if (!$session)
      return $this->unauthorized();

    $estudante = Estudante::find($estudanteId);

    if (\is_null($estudante))
      throw new UniversityMarketException("Estudante não encontrado");

    // Construir model de detalhes do estudante
    $model = new EstudanteDadosModel();

    $model->nome = $estudante->nome;
    $model->email = $estudante->email;
    $model->cursoNome = $estudante->curso->nome;

    return $this->response($model);
  }

  public function criar(Request $request) {

    $model = $this->cast($request, EstudanteCriacaoModel::class);

    $model->validar();

    $existente = $this->estudanteExistente($model->email);

    if ($existente !== false) {

      throw new UniversityMarketException("Estudante já possui cadastro em $existente");
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

    // Persistir log de criacao do estudante
    UniversityMarketLogger::log(
      UniversityMarketResource::$estudante,
      $estudante->id,
      StdLogType::$criacao,
      "Estudante Criado",
      null,
      null
    );

  }

  public function cadastrarContato(Request $request){
    
    $model = $this->cast($request, EstudanteContatosModel::class);

    // Validar informacoes construidas na model
    $model->validar();

    $session = $this->getSession();

    if (!$session)
        return $this->unauthorized();
  
    //Valida se o tipo de contato já está cadastrado
    $validacao = Contato::where('estudante_id',$model->estudante_id)
      ->where('tipo_contato_id',$model->tipo_contato_id)
      ->get()->toArray();

    if($validacao)
      throw new UniversityMarketException("Tipo de contato já cadastrado, favor edita-lo!");
    
    $contato = new Contato;

    $contato->conteudo = $model->conteudo;
    $contato->tipo_contato_id = $model->tipo_contato_id;
    $contato->estudante_id = $model->estudante_id ?? $session->estudante_id;

    $contato->save();

    // Persistir log de criacao de contato do estudante
    UniversityMarketLogger::log(
      UniversityMarketResource::$contato,
      $contato->id,
      StdLogType::$criacao,
      "Contato criado",
      $session->estudante_id,
      null
    );

    
    return $this->response();
  }

  public function obterContatos($estudanteId){

    $session = $this->getSession();

      if (!$session)
          return $this->unauthorized();

      $contatos = Contato::where('estudante_id', $estudanteId)
        ->where('deleted',false)
        ->get()->toArray();
                              
      $model = $this->cast($contatos, EstudanteContatosModel::class);

      return $this->response($model);
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

  private function estudanteBloqueado($estudante_id) {

    $estudante = Bloqueios::where('estudante_id', $estudante_id)->first();

    if (is_null($estudante))
      return false;

    return $estudante;
  }


  public function bloquear(Request $request) {

    $model = $this->cast($request, EstudanteBloqueioModel::class);    

    $existente = $this->estudanteBloqueado($model->estudante_id);

    if ($existente) {
      throw new \Exception("Estudante já está bloqueado");
    }

    $bloqueio = new Bloqueios();

    $bloqueio->estudante_id = $model->estudante_id;
    $bloqueio->motivo = $model->motivo;
    $bloqueio->finished_at = $model->finished_at;

    $bloqueio->save();

    // Log de criacao
  }

}