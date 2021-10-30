<?php

namespace App\Http\Controllers\Instituicao;

use App\Common\Datatype\KeyValuePair;
use App\Exceptions\Base\UMException;
use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models de conta utilizadas
use App\Models\Instituicao\Instituicao;
use App\Http\Controllers\Instituicao\Models\InstituicaoCriacaoModel;
use App\Http\Controllers\Instituicao\Models\InstituicaoListaModel;
use stdClass;

class InstituicaoController extends UniversityMarketController {

  public function cadastrar(Request $request) {

    $model = $this->cast($request, InstituicaoCriacaoModel::class);

    $model->validar();

    // Validacao se ja existe um cadastro ativo para a instituicao
    $hasCadastro = Instituicao::where('ativa', true)
      ->where(
        function($query) use ($model) {

          $query->where('cnpj', $model->cnpj)
            ->orWhere('razao_social', $model->razaoSocial);
        }
      )->first();

    if (!\is_null($hasCadastro))
      throw new UMException("A instituição de ensino já possui um cadastro");

    $instituicao = new Instituicao();

    $instituicao->nome_fantasia = $model->nomeFantasia;
    $instituicao->razao_social = $model->razaoSocial;
    $instituicao->cnpj = $model->cnpj;
    $instituicao->email = $model->email;
    $instituicao->ativa = false; // Cadastro deve iniciar desativado
    $instituicao->approved_at = null; // Somente quando aprovada
    $instituicao->plano_id = null; // Quando sistema de planos estiver implementado

    $instituicao->save();

    return $this->response($instituicao->id);
  }

  public function ativar($instituicaoId) {

    return $this->alterarStatusAtiva($instituicaoId, true);
  }

  public function desativar($instituicaoId) {

    return $this->alterarStatusAtiva($instituicaoId, false);
  }

  public function aprovar($instituicaoId) {

    $session = $this->getSession();

    $instituicao = Instituicao::find($instituicaoId);

    if (is_null($instituicao))
      throw new UMException("Instituição não encontrada");

    if (!is_null($instituicao->approved_at))
      throw new UMException("Essa instituição já teve o cadastro aprovado");

    $instituicao->approved_at = date($this->datetime_format);
    $instituicao->save();

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
      throw new \Exception("Instituição não encontrada");

    if (is_null($instituicao->approved_at))
      throw new \Exception("Cadastro da instituição ainda não foi aprovado");

    if ($instituicao->ativa == $novoStatus) {
      
      $currentStatus = $instituicao->ativa ? "ativa" : "desativada";
      throw new \Exception("A instituição já está registrada como $currentStatus");
    }

    $instituicao->ativa = $novoStatus;
    $instituicao->save();

    return $this->response();
  }

}