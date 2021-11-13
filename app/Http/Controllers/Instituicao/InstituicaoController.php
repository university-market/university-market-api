<?php

namespace App\Http\Controllers\Instituicao;

use Illuminate\Http\Request;

// Base
use App\Base\Controllers\UniversityMarketController;
use App\Base\Exceptions\UniversityMarketException;
use App\Base\Logs\Logger\UniversityMarketLogger;
use App\Base\Resource\UniversityMarketResource;

// Logs
use App\Base\Logs\Type\StdLogType;

// Common
use App\Common\Datatype\KeyValuePair;

// Entidades
use App\Models\Instituicao\Instituicao;

// Models de instituicao utilizadas
use App\Http\Controllers\Instituicao\Models\InstituicaoListaModel;
use App\Http\Controllers\Instituicao\Models\InstituicaoCriacaoModel;

class InstituicaoController extends UniversityMarketController {

  /**
   * Cadastrar Instituicao de ensino
   * 
   * @method cadastrar
   * @param Request $request Instância de requisição - Cast para `InstituicaoCriacaoModel`
   * 
   * @type Http POST
   * @route ``
   */
  public function cadastrar(Request $request) {

    $model = $this->cast($request, InstituicaoCriacaoModel::class);
    $model->validar();

    $instituicao = new Instituicao();

    $instituicao->nome_fantasia = $model->nomeFantasia;
    $instituicao->razao_social = $model->razaoSocial;
    $instituicao->cnpj = $model->cnpj;
    $instituicao->email = $model->email;
    $instituicao->ativa = false; // Cadastro deve iniciar desativado
    $instituicao->approved_at = null; // Somente quando aprovada
    $instituicao->plano_id = null; // Quando sistema de planos estiver implementado

    $instituicao->save();

    // Persistir log de criacao de contato da instituicao
    UniversityMarketLogger::log(
      UniversityMarketResource::$instituicao,
      $instituicao->id,
      StdLogType::$criacao,
      "Instituição criada",
      null,
      null
    );

    return $this->response();
  }

  public function ativar($instituicaoId) {

    // Log de ativacao de instituicao

    return $this->alterarStatusAtiva($instituicaoId, true);
  }

  public function desativar($instituicaoId) {

    // Log de desativacao de instituicao

    return $this->alterarStatusAtiva($instituicaoId, false);
  }

  public function aprovar($instituicaoId) {

    $session = $this->getSession();

    $instituicao = Instituicao::find($instituicaoId);

    if (is_null($instituicao))
      throw new UniversityMarketException("Instituição não encontrada");

    if (!is_null($instituicao->approved_at))
      throw new UniversityMarketException("Essa instituição já teve o cadastro aprovado");

    $instituicao->approved_at = date($this->datetime_format);
    $instituicao->save();

    // Log de aprovacao de cadastro de instituicao

    return $this->response();
  }

  public function listarDisponiveis() {

    $instituicoes = Instituicao::where('ativa', true)->where('approved_at', '!=', null)->get();
    $arr = [];

    foreach ($instituicoes as $e) {

      $element = new KeyValuePair();

      $element->key = $e->id;
      $element->value = $e->razao_social;

      $arr[] = $element;
    }
    
    return $arr;
  }

  public function listarTodas() {

    $instituicoes = Instituicao::all()->getDictionary();

    $listaModels = [];

    foreach ($instituicoes as $instituicao) {

      $model = new InstituicaoListaModel();

      $model->instituicaoId = $instituicao->id;
      $model->razaoSocial = $instituicao->razao_social;
      $model->nomeFantasia = $instituicao->nome_fantasia;
      $model->cnpj = $instituicao->cnpj;
      $model->email = $instituicao->email;
      $model->dataHoraCadastro = $instituicao->created_at;
      $model->aprovada = !is_null($instituicao->approved_at);
      $model->ativa = $instituicao->ativa;

      $listaModels[] = $model;
    }
    
    return $this->response($listaModels);
  }

  // Private methods

  private function alterarStatusAtiva($instituicaoId, $novoStatus) {

    $instituicao = Instituicao::find($instituicaoId);

    if (is_null($instituicao))
      throw new UniversityMarketException("Instituição não encontrada");

    if (is_null($instituicao->approved_at))
      throw new UniversityMarketException("Cadastro da instituição ainda não foi aprovado");

    if ($instituicao->ativa == $novoStatus) {
      
      $currentStatus = $instituicao->ativa ? "ativa" : "desativada";
      throw new UniversityMarketException("A instituição já está registrada como $currentStatus");
    }

    $instituicao->ativa = $novoStatus;
    $instituicao->save();

    return $this->response();
  }

}