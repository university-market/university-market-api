<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models de conta utilizadas
use App\Models\Instituicao;
use App\Http\Controllers\Instituicao\Models\InstituicaoCriacaoModel;

class InstituicaoController extends UniversityMarketController {

  public function cadastrar(Request $request) {

    $model = $this->cast($request, InstituicaoCriacaoModel::class);

    $model->validar();

    // Validacao se ja existe um cadastro ativo para a instituicao
    $hasCadastro = Instituicao::where('ativa', true)
      ->where(
        function($query) use ($model) {

          $query->where('cnpj', $model->cnpj)
            ->orWhere('razaoSocial', $model->razaoSocial)
            ->orWhere('cpfRepresentante', $model->cpfRepresentante);
        }
      )->first();

    if (!\is_null($hasCadastro))
      throw new \Exception("Já existe um cadastro que corresponde aos dados informados");

    $instituicao = new Instituicao();

    $instituicao->nomeFantasia = $model->nomeFantasia;
    $instituicao->razaoSocial = $model->razaoSocial;
    $instituicao->nomeReduzido = null; // Cadastro posterior
    $instituicao->cnpj = $model->cnpj;
    $instituicao->cpfRepresentante = $model->cpfRepresentante;
    $instituicao->emailContato = $model->emailContato;
    $instituicao->etapaCadastroId = 1; // Solicitacao enviada
    $instituicao->dataHoraCadastro = date($this->dateTimeFormat);
    $instituicao->dataHoraAprovacao = null; // Somente quando aprovada
    $instituicao->ativa = true; // Cadastro ativo

    $instituicao->save();

    return response()->json($instituicao->instituicaoId);
  }

  public function ativar($instituicaoId) {

    return $this->alterarStatusAtiva($instituicaoId, true);
  }

  public function desativar($instituicaoId) {

    return $this->alterarStatusAtiva($instituicaoId, false);
  }

  // Private methods

  private function alterarStatusAtiva($instituicaoId, $novoStatus) {

    $instituicao = Instituicao::where('instituicaoId', $instituicaoId)->first();

    if (\is_null($instituicao))
      throw new \Exception("Instituição não encontrada");

    if ($instituicao->ativa == $novoStatus) {
      
      $currentStatus = $instituicao->ativa ? "ativa" : "desativada";
      throw new \Exception("A instituição já está registrada como $currentStatus");
    }

    $instituicao->ativa = $novoStatus;
    $instituicao->save();

    return response(null, 200);
  }

}