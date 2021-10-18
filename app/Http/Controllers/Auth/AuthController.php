<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Email\EmailHelper;
use App\Helpers\Email\EmailTemplateIdentifier;
use App\Helpers\Token\TokenHelper;
use App\Http\Controllers\Auth\Data\AuthCommonData;
use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models de publicacao utilizadas
use App\Models\Session\AppSession;
use App\Http\Controllers\Auth\Models\AppLoginModel;
use App\Http\Controllers\Auth\Models\AppSummarySession;
use App\Http\Controllers\Auth\Models\RecuperacaoSenhaEstudanteModel;
use App\Http\Controllers\Auth\Models\AlteracaoSenhaModel;
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

    $activatedSession = AppSession::where('estudanteId', $estudante->estudanteId)->get();

    // Limpar session existente
    if (count($activatedSession) > 1) {

      $sessionIds = array_map(function($session) {
        return $session['sessionId'];
      }, $activatedSession->toArray());

      AppSession::destroy($sessionIds);

      $activatedSession = null;

    } elseif (count($activatedSession) == 1) {

      $activatedSession = $activatedSession[0];
      
      // Validar expiration time da sessao ativa
      if ($activatedSession->expirationTime < time()) {

        AppSession::destroy($activatedSession->sessionId);

        $activatedSession = null;
      }
    } else {

      $activatedSession = null;
    }

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

  public function solicitarRecuperacaoSenhaEstudante(Request $request) {

    $email = $request->only('email')['email'];

    if (is_null($email) || empty(trim($email)))
      throw new Exception("E-mail informado inválido");

    $email = trim($email);

    $estudante = Estudante::where('ativo', true)->where('email', $email)->first();

    // Validar existencia do estudante solicitante
    if (is_null($estudante))
      throw new Exception("Não há cadastro relacionado a este endereço de e-mail");

    $activatedSession = AppSession::where('estudanteId', $estudante->estudanteId)->first();

    // Validar existencia do estudante solicitante
    if (!is_null($activatedSession))
      throw new Exception("Existe uma sessão ativa neste endereço de e-mail");

    $solicitacaoExistente = RecuperacaoSenhaEstudante::where('estudanteId', $estudante->estudanteId)
      ->where('completo', false)
      ->where('expirada', false)
      ->first();

    if (!is_null($solicitacaoExistente)) {

      if ($solicitacaoExistente->tempoExpiracao < time()) {

        $solicitacaoExistente->expirada = true;
        $solicitacaoExistente->save();

      } else {

        $model = new RecuperacaoSenhaEstudanteModel();
  
        $expirationTimeInMinutes = ($solicitacaoExistente->tempoExpiracao - time()) / 60;
  
        $model->expirationTime = intval(ceil($expirationTimeInMinutes)); // In minutes
        $model->existente = true;
  
        return response()->json($model);
      }
    }

    $token = TokenHelper::generatePasswordResetToken();

    $expirationTime = 15; // In minutes
    $solicitacaoDate = date($this->dateTimeFormat);

    $primeiroNome = explode(' ', $estudante->nome)[0];

    $email_data = [
      'email' => $email,
      'estudanteNome' => $primeiroNome,
      'token' => $token,
      'expirationTime' => $expirationTime,
      'requestDate' => $solicitacaoDate
    ];

    // Enviar e-mail
    EmailHelper::send(null, $email_data, EmailTemplateIdentifier::$recuperarSenha);

    // Persistir solicitação de recuperação de senha
    $recuperacao = new RecuperacaoSenhaEstudante();

    $recuperacao->tokenRecuperacao = $token;
    $recuperacao->tempoExpiracao = time() + $expirationTime * 60; // In seconds
    $recuperacao->email = $email;
    $recuperacao->dataHoraSolicitacao = $solicitacaoDate;
    $recuperacao->completo = false;
    $recuperacao->estudanteId = $estudante->estudanteId;

    $recuperacao->save();

    return response()->json(new RecuperacaoSenhaEstudanteModel($expirationTime));
  }

  public function validarTokenRecuperacaoSenhaEstudante($token, $obter = false) {

    $solicitacao = RecuperacaoSenhaEstudante::where('tokenRecuperacao', $token)
      ->where('completo', false)
      ->where('expirada', false)
      ->first();

    if (!is_null($solicitacao)) {

      if ($solicitacao->tempoExpiracao < time()) {

        $solicitacao->expirada = true;
        $solicitacao->save();

        if ($obter)
          return null;

        throw new Exception("Link expirado. Uma nova solicitação deve ser realizada");
      }

      if ($obter)
        return $solicitacao;

      return response()->json(true);
    }

    if ($obter)
      return null;

    throw new Exception("Não foi possível encontrar esta página");
  }

  public function validarEmailRecuperacaoSenhaEstudante(Request $request, $privateGet = false) {

    $data = $request->only(['email', 'token']);

    $estudante = Estudante::where('email', $data['email'])->where('ativo', true)->first();

    if (is_null($estudante))
      throw new Exception('Este e-mail não pertence à um estudante ativo');

    $solicitacao = $this->validarTokenRecuperacaoSenhaEstudante($data['token'], true);

    if (is_null($solicitacao)) {

      if ($privateGet)
        return null;

      throw new Exception("Não há solicitação de redefinição de senha ativa");
    }

    $is_valid = false;

    if ($solicitacao->estudante->estudanteId == $estudante->estudanteId) {

      $is_valid = true;
    }

    return $privateGet ? $solicitacao : response()->json($is_valid);
  }

  public function alterarSenhaEstudante(Request $request) {

    $model = $this->cast($request, AlteracaoSenhaModel::class);

    $solicitacao = $this->validarEmailRecuperacaoSenhaEstudante($request, true);

    if (is_null($solicitacao))
      throw new Exception("Não há mais uma solicitação ativa para este e-mail");

    $maxTime = $solicitacao->tempoExpiracao + 5 * 60; // Tolerância de 5 minutos além do tempo de expiração

    if ($maxTime < time())
      throw new Exception("Esta solicitação expirou. Uma nova solicitação é necessária");

    $estudante = Estudante::where('email', $model->email)->where('ativo', true)->first();

    if (is_null($estudante))
      throw new Exception("Estudante não localizado");

    if (Hash::check($model->senha, $estudante->hashSenha))
      throw new Exception("A nova senha não pode ser igual à anterior");

    // Salvar nova senha para o estudante
    $estudante->hashSenha = Hash::make($model->senha);
    $estudante->save();

    // Finalizar a solicitação de redefinição
    $solicitacao->completo = true;
    $solicitacao->save();
  }

  // Private methods

  private function generateExpirationDate() {

    $minutes = AuthCommonData::getSessionDefaultExpirationTime();
    
    $timestamp = time() + $minutes * 60; // now + ($minutes * 60 seconds)

    return $timestamp;
  }
}