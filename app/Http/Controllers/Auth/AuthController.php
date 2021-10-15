<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Token\TokenHelper;
use App\Http\Controllers\Auth\Data\AuthCommonData;
use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models de publicacao utilizadas
use App\Models\Session\AppSession;
use App\Http\Controllers\Auth\Models\AppLoginModel;
use App\Http\Controllers\Auth\Models\AppSummarySession;
use App\Models\Estudante\RecuperacaoSenhaEstudante;
use App\Models\Estudante\Estudante;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthController extends UniversityMarketController {

  public function loginEstudante(Request $request) {

    $model = $this->cast($request, AppLoginModel::class);

    $model->validar();

    $estudante = Estudante::where('email', $model->email)->first();

    if (is_null($estudante) || !Hash::check($model->senha, $estudante->hashSenha))
      throw new \Exception("E-mail ou senha incorretos");

    $activatedSession = AppSession::where('estudanteId', $estudante->estudanteId)->first();

    // Retornar token da sessao ativa
    if (!is_null($activatedSession)) {

      return response()->json(new AppSummarySession(
        $activatedSession->sessionToken, 
        $activatedSession->estudanteId,
        $estudante->nome
      ));
    }

    // Gerar tempo de expiracao da sessao em minutos
    $expiration = $this->generateExpirationDate();

    $session = new AppSession();

    $session->estudanteId = $estudante->estudanteId;
    $session->sessionToken = TokenHelper::generateSessionToken();
    $session->expirationTime = $expiration;

    $session->save();

    return response()->json(new AppSummarySession(
      $session->sessionToken, 
      $session->estudanteId,
      $estudante->nome
    ));
  }

  public function solicitarRecuperacaoSenhaEstudante($email) {

    if (is_null($email))
      throw new Exception("E-mail informado inválido");

    $estudante = Estudante::where('ativo', true)->where('email', $email)->first();

    if (is_null($estudante))
      throw new Exception("Não há cadastro relacionado a este endereço de e-mail");

    $token = TokenHelper::generatePasswordResetToken();

    $expirationTime = 15; // In minutes

    $email_data = [
      'email' => $email,
      'estudanteNome' => $estudante->nome,
      'token' => $token,
      'expirationTime' => $expirationTime
    ];

    // Enviar e-mail

    // Persistir solicitação de recuperação de senha
    $recuperacao = new RecuperacaoSenhaEstudante();

    $recuperacao->tokenRecuperacao = $token;
    $recuperacao->tempoExpiracao = $expirationTime * 60; // In seconds
    $recuperacao->email = $email;
    $recuperacao->dataHoraSolicitacao = date($this->dateTimeFormat);
    $recuperacao->completo = false;
    $recuperacao->estudanteId = $estudante->estudanteId;

    $recuperacao->save();
  }

  // Private methods

  private function generateExpirationDate() {

    $minutes = AuthCommonData::getSessionDefaultExpirationTime();
    
    $timestamp = time() + $minutes * 60; // now + ($minutes * 60 seconds)

    return $timestamp;
  }
}