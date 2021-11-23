<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

// Base
use App\Base\Controllers\UniversityMarketController;
use App\Base\Exceptions\UniversityMarketException;

// Common
use Illuminate\Support\Facades\Hash;

// Entidades
use App\Models\Usuario\Usuario;
use App\Models\Estudante\Estudante;

// Models de autenticacao utilizadas
use App\Http\Controllers\Auth\Models\LoginModel;
use App\Http\Controllers\Auth\Models\LoginResponseModel;

// Repositories
use App\Repositories\Auth\AuthRepository;

class AuthController extends UniversityMarketController {

  /**
	 * AuthRepository Instance - métodos comuns do módulo de autenticação
	 */
	protected $auth_repository;

	function __construct(AuthRepository $auth_repository)
	{
		$this->auth_repository = $auth_repository;
	}

	// =========================================================================================================

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

      $owner = Usuario::findByEmail($model->email);

    } elseif ($requestType == SESSION_TYPE_ESTUDANTE) {

      $owner = Estudante::findByEmail($model->email);

    } else {

      $owner = null;
    }

    if (is_null($owner) || !Hash::check($model->senha, $owner->senha))
      throw new UniversityMarketException("E-mail ou senha incorretos");

    $activatedSession = $this->auth_repository->getActivatedSession($owner->id, $requestType);

    if (!is_null($activatedSession)) {

      return $this->response(
        new LoginResponseModel($activatedSession->token)
      );
    }

    $session = $this->auth_repository->createSession($owner, $requestType);

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

    $this->auth_repository->destroySession($session->id);

    return $this->response();
  }

}