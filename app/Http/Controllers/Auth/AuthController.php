<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Base\UMException;
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
use App\Models\Estudante\RecuperacaoSenha;
use App\Models\Estudante\Estudante;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthController extends UniversityMarketController {

  public function loginEstudante(Request $request) {

    $model = $this->cast($request, AppLoginModel::class);

    $model->validar();

    $estudante = Estudante::where('email', $model->email)->first();

    if (is_null($estudante) || !Hash::check($model->senha, $estudante->senha))
      throw new UMException("E-mail ou senha incorretos");

    $activatedSession = AppSession::where('estudante_id', $estudante->id)->get();

    // Limpar session existente
    if (count($activatedSession) > 1) {

      $sessionIds = array_map(function($session) {
        return $session['id'];
      }, $activatedSession->toArray());

      AppSession::destroy($sessionIds);

      $activatedSession = null;

    } elseif (count($activatedSession) == 1) {

      $activatedSession = $activatedSession[0];
      
      // Validar expiration time da sessao ativa
      if ($activatedSession->expiration_time < time()) {

        AppSession::destroy($activatedSession->id);

        $activatedSession = null;
      }
    } else {

      $activatedSession = null;
    }

    // Retornar token da sessao ativa
    if (!is_null($activatedSession)) {

      return $this->response(new AppSummarySession(
        $activatedSession->token,
        $estudante->id,
        $estudante->nome
      ));
    }

    // Gerar tempo de expiracao da sessao em minutos
    $expiration = $this->generateExpirationDate();

    $session = new AppSession();

    $session->estudante_id = $estudante->id;
    $session->token = TokenHelper::generateSessionToken();
    $session->expiration_time = $expiration;

    $session->save();

    return response()->json(new AppSummarySession(
      $session->token, 
      $estudante->id,
      $estudante->nome
    ));
  }

  public function solicitarRecuperacaoSenhaEstudante(Request $request) {

    $email = $request->only('email')['email'];

    if (is_null($email) || empty(trim($email)))
      throw new UMException("E-mail informado inválido");

    $email = trim($email);

    $estudante = Estudante::where('ativo', true)->where('email', $email)->first();

    // Validar existencia do estudante solicitante
    if (is_null($estudante))
      throw new UMException("Não há cadastro relacionado a este endereço de e-mail");

    $activatedSession = AppSession::where('estudante_id', $estudante->id)->first();

    // Validar existencia do estudante solicitante
    if (!is_null($activatedSession))
      throw new UMException("Existe uma sessão ativa neste endereço de e-mail");

    $solicitacaoExistente = RecuperacaoSenha::where('estudante_id', $estudante->id)
      ->where('completa', false)
      ->where('expirada', false)
      ->first();

    if (!is_null($solicitacaoExistente)) {

      if ($solicitacaoExistente->expiration_at < time()) {

        $solicitacaoExistente->expirada = true;
        $solicitacaoExistente->save();

      } else {

        $model = new RecuperacaoSenhaEstudanteModel();
  
        $expirationTimeInMinutes = ($solicitacaoExistente->expiration_at - time()) / 60;
  
        $model->expirationTime = intval(ceil($expirationTimeInMinutes)); // In minutes
        $model->existente = true;
  
        return response()->json($model);
      }
    }

    $token = TokenHelper::generatePasswordResetToken();

    $expirationTime = 15; // In minutes
    $solicitacaoDate = date($this->datetime_format);

    $primeiroNome = explode(' ', $estudante->nome)[0];

    $payload = [
      'email' => $email,
      'estudanteNome' => $primeiroNome,
      'token' => $token,
      'expirationTime' => $expirationTime,
      'requestDate' => $solicitacaoDate
    ];

    // Enviar e-mail
    EmailHelper::send(null, $payload, EmailTemplateIdentifier::$recuperarSenha);

    // Persistir solicitação de recuperação de senha
    $recuperacao = new RecuperacaoSenha();

    $recuperacao->token = $token;
    $recuperacao->email = $email;
    $recuperacao->completa = false;
    $recuperacao->expirada = false;
    $recuperacao->expiration_at = time() + $expirationTime * 60; // In seconds
    $recuperacao->estudante_id = $estudante->id;

    $recuperacao->save();

    return response()->json(new RecuperacaoSenhaEstudanteModel($expirationTime));
  }

  public function validarTokenRecuperacaoSenhaEstudante($token, $obter = false) {

    $solicitacao = RecuperacaoSenha::with('estudante')
      ->where('token', $token)
      ->where('completa', false)
      ->where('expirada', false)
      ->first();

    if (!is_null($solicitacao)) {

      if ($solicitacao->expiration_at < time()) {

        $solicitacao->expirada = true;
        $solicitacao->save();

        if ($obter)
          return null;

        throw new UMException("Link expirado. Uma nova solicitação deve ser realizada");
      }

      if ($obter)
        return $solicitacao;

      return response()->json(true);
    }

    if ($obter)
      return null;

    throw new UMException("Não foi possível encontrar esta página");
  }

  public function validarEmailRecuperacaoSenhaEstudante(Request $request, $privateGet = false) {

    $data = $request->only(['email', 'token']);

    $estudante = Estudante::where('email', $data['email'])->where('ativo', true)->first();

    if (is_null($estudante))
      throw new UMException('Este e-mail não pertence à um estudante ativo');

    $solicitacao = $this->validarTokenRecuperacaoSenhaEstudante($data['token'], true);

    if (is_null($solicitacao)) {

      if ($privateGet)
        return null;

      throw new UMException("Não há solicitação de redefinição de senha ativa");
    }

    $is_valid = false;

    if ($solicitacao->estudante->id == $estudante->id) {

      $is_valid = true;
    }

    return $privateGet ? $solicitacao : response()->json($is_valid);
  }

  public function alterarSenhaEstudante(Request $request) {

    $model = $this->cast($request, AlteracaoSenhaModel::class);

    $solicitacao = $this->validarEmailRecuperacaoSenhaEstudante($request, true);

    if (is_null($solicitacao))
      throw new UMException("Não há mais uma solicitação ativa para este e-mail");

    $maxTime = $solicitacao->expiration_at + 5 * 60; // Tolerância de 5 minutos além do tempo de expiração

    if ($maxTime < time())
      throw new UMException("Esta solicitação expirou. Uma nova solicitação é necessária");

    $estudante = Estudante::where('email', $model->email)->where('ativo', true)->first();

    if (is_null($estudante))
      throw new UMException("Estudante não localizado");

    if (Hash::check($model->senha, $estudante->senha))
      throw new UMException("A nova senha não pode ser igual à anterior");

    // Salvar nova senha para o estudante
    $estudante->senha = Hash::make($model->senha);
    $estudante->save();

    // Finalizar a solicitação de redefinição
    $solicitacao->completa = true;
    $solicitacao->save();
  }

  // Private methods

  private function generateExpirationDate() {

    $minutes = AuthCommonData::getSessionDefaultExpirationTime();
    
    $timestamp = time() + $minutes * 60; // now + ($minutes * 60 seconds)

    return $timestamp;
  }
}