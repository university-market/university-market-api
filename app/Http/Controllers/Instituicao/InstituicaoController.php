<?php

namespace App\Http\Controllers\Instituicao;

use App\Exceptions\Base\UMException;
use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models de conta utilizadas
use App\Models\Instituicao;
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
            ->orWhere('razaoSocial', $model->razaoSocial);
        }
      )->first();

    if (!\is_null($hasCadastro))
      throw new UMException("A instituição de ensino já possui um cadastro");

    $instituicao = new Instituicao();

    $instituicao->nomeFantasia = $model->nomeFantasia;
    $instituicao->razaoSocial = $model->razaoSocial;
    $instituicao->nomeReduzido = null; // Cadastro posterior
    $instituicao->cnpj = $model->cnpj;
    $instituicao->email = $model->email;
    $instituicao->telefone = $model->telefone;
    $instituicao->dataHoraCadastro = date($this->dateTimeFormat);
    $instituicao->aprovada = false; // True somente quando aprovada
    $instituicao->ativa = true; // Cadastro ativo
    $instituicao->planoId = null; // Quando sistema de planos estiver implementado

    $instituicao->save();

    return response()->json($instituicao->instituicaoId);
  }

  public function ativar($instituicaoId) {

    return $this->alterarStatusAtiva($instituicaoId, true);
  }

  public function desativar($instituicaoId) {

    return $this->alterarStatusAtiva($instituicaoId, false);
  }

  public function aprovar($instituicaoId) {

    $session = $this->getSession();

    // if (!$session)
    //   throw new \Exception("Sem permissão para realizar esta operação");

    $instituicao = Instituicao::where('instituicaoId', $instituicaoId)->first();

    if (\is_null($instituicao))
      throw new \Exception("Instituição não encontrada");

    if ($instituicao->aprovada)
      throw new \Exception("Essa instituição já teve o cadastro aprovado");

    $instituicao->aprovada = true;

    $instituicao->save();

    return response(null, 200);
  }

  public function listarDisponiveis() {

    $instituicoes = Instituicao::where('ativa', true)->where('aprovada', true)->get();
    $arr = [];

    foreach ($instituicoes as $e) {

      $element = new stdClass;
      $element->key = $e->instituicaoId;
      $element->value = $e->razaoSocial;

      $arr[] = $element;
    }
    
    return $arr;
  }

  public function listarTodas() {

    $instituicoes = Instituicao::all()->getDictionary();
    
    return $this->cast($instituicoes, InstituicaoListaModel::class);
  }

  // Private methods

  private function alterarStatusAtiva($instituicaoId, $novoStatus) {

    $instituicao = Instituicao::where('instituicaoId', $instituicaoId)->first();

    if (\is_null($instituicao))
      throw new \Exception("Instituição não encontrada");

    if (!$instituicao->aprovada)
      throw new \Exception("Cadastro da instituição ainda não foi aprovado");

    if ($instituicao->ativa == $novoStatus) {
      
      $currentStatus = $instituicao->ativa ? "ativa" : "desativada";
      throw new \Exception("A instituição já está registrada como $currentStatus");
    }

    $instituicao->ativa = $novoStatus;
    $instituicao->save();

    return response(null, 200);
  }

}