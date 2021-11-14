<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

// Base
use App\Base\Controllers\UniversityMarketController;
use App\Base\Exceptions\UniversityMarketException;

// Common
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Auth\Data\AuthCommonData;

// Helpers
use App\Helpers\Token\TokenHelper;
use App\Helpers\Email\EmailHelper;
use App\Helpers\Email\EmailTemplate;

// Entidades
use App\Models\Session\AppSession;
use App\Models\Estudante\Estudante;
use App\Models\Estudante\RecuperacaoSenha;

// Models de autenticacao utilizadas
use App\Http\Controllers\Auth\Models\LoginModel;
use App\Http\Controllers\Auth\Models\AlteracaoSenhaModel;
use App\Http\Controllers\Auth\Models\LoginResponseModel;
use App\Http\Controllers\Auth\Models\RecuperacaoSenhaEstudanteModel;

class AuthController extends UniversityMarketController {

  /**
   * Login na plataforma (para qualquer session type)
   * 
   * @method login
   * @param Request $request Type Casting para `LoginModel` - dados de login
   * 
   * @type Http POST
   * @route `/login`
   */
  public function login(Request $request) {

    $model = $this->cast($request, LoginModel::class);
    $model->validar();

    $requestType = $this->getRequestSource();

    $owner = null;

    if ($requestType == SESSION_TYPE_ADMIN) {


    } elseif ($requestType == SESSION_TYPE_ESTUDANTE) {

      $owner = Estudante::findByEmail($model->email);

    } else {

      $owner = null;
    }

    if (is_null($owner) || !Hash::check($model->senha, $owner->senha))
      throw new UniversityMarketException("E-mail ou senha incorretos");

    $activatedSession = $this->getActivatedSession($owner->id, $requestType);

    if (!is_null($activatedSession)) {

      return $this->response(
        new LoginResponseModel($activatedSession->token)
      );
    }

    $session = $this->createSession($owner, $requestType);

    return $this->response(
      new LoginResponseModel($session->token)
    );
  }

  /**
   * Logout da plataforma (para qualquer session type)
   * 
   * @method logout
   * 
   * @type Http POST
   * @route `/logout`
   */
  public function logout() {

    $session = $this->getSession();

    if (is_null($session))
      return $this->unauthorized("Não há login ativo");

    $this->destroySession($session->id);

    return $this->response();
  }

  // Private methods

  /**
   * Timestamp de sessão com base na parametrizacao de `AuthCommonData`
   * 
   * @method generateExpirationDate
   * 
   * @return int Timestamp da expiração da session
   */
  private function generateExpirationDate() {

    $minutes = AuthCommonData::getSessionDefaultExpirationTime();
    
    $timestamp = time() + $minutes * 60; // now + ($minutes * 60 seconds)

    return $timestamp;
  }

  /**
   * Obtem session ativa no banco de dados, de acordo com o tipo de sessao solicitada
   * 
   * @method getActivatedSession
   * 
   * @param int $owner_id Id de Estudante|Usuario do sistema (ambos do tipo `UniversityMarketActorBase`)
   * @param SESSION_TYPE_ESTUDANTE|SESSION_TYPE_ADMIN $session_type Tipo de sessão solicitada para obtenção
   * 
   * @return \App\Models\Session\BaseSession|null Instância da sessão existente (ativa e válida) ou null caso não exista
   */
  private function getActivatedSession($owner_id, $session_type) {

    if (is_null($owner_id) || is_null($session_type))
      throw new UniversityMarketException("Id ou tipo de sessão não informado");

    $owner_id_session_field = null;

    switch ($session_type) {

      case SESSION_TYPE_ADMIN:
        $owner_id_session_field = 'usuario_id';
        break;

      case SESSION_TYPE_ESTUDANTE:
        $owner_id_session_field = 'estudante_id';
        break;
    }

    if (is_null($owner_id_session_field))
      throw new UniversityMarketException("Tipo de sessão inválido");

    $session = AppSession::where($owner_id_session_field, $owner_id)->get();

    // Limpar session existente
    if (count($session) > 1) {

      $last_session = $session[count($session)-1];

      $sessionIds = array_map(function($session) use($last_session) {
        if ($session['id'] == $last_session['id'])
          return;

        return $session['id'];
      }, $session->toArray());

      AppSession::destroy($sessionIds);

      if ($last_session->expiration_time < time()) {

        AppSession::destroy($last_session->id);

        $session = null;

      } else {

        $session = $last_session;
      }

    } elseif (count($session) == 1) {

      $session = $session[0];
      
      // Validar expiration time da sessao ativa
      if ($session->expiration_time < time()) {

        AppSession::destroy($session->id);

        $session = null;
      }
    } else {

      $session = null;
    }

    return $session;
  }

  /**
   * Cria uma instância de sessão no banco de dados e retorna a mesma intância
   * 
   * @method createSession
   * 
   * @param UniversityMarketActorBase $owner Entidade de Estudante|Usuario do sistema (ambos do tipo `UniversityMarketActorBase`)
   * @param SESSION_TYPE_ESTUDANTE|SESSION_TYPE_ADMIN $session_type Tipo de sessão solicitada para criação
   * 
   * @return \App\Models\Session\BaseSession Instância da sessão criada
   */
  private function createSession($owner, $session_type) {

    // Gerar tempo de expiracao da sessao em minutos
    $expiration = $this->generateExpirationDate();

    $session = new AppSession();

    $session->estudante_id = $session_type == SESSION_TYPE_ESTUDANTE ? $owner->id : null;
    $session->usuario_id = $session_type == SESSION_TYPE_ADMIN ? $owner->id : null;

    if ($session->estudante_id == null && $session->usuario_id == null)
      throw new UniversityMarketException("Não foi possível criar a sessão");

    $session->token = TokenHelper::generateSessionToken();
    $session->expiration_time = $expiration;

    $session->save();

    return $session;
  }

  /**
   * Exclui definitivamente uma session do banco de dados
   * 
   * @method destroySession
   * 
   * @param int $session_id Id da sessão a ser excluida
   * 
   * @return void
   */
  private function destroySession($session_id) {

    AppSession::destroy($session_id);
  }
}