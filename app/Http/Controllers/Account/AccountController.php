<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;

// Base
use App\Base\Controllers\UniversityMarketController;
use App\Base\Exceptions\UniversityMarketException;

// Models utilizadas
use App\Http\Controllers\Account\Models\ProfileUsuarioModel;
use App\Http\Controllers\Account\Models\ProfileEstudanteModel;

class AccountController extends UniversityMarketController {

  /**
   * Obter model de perfil - Conta estudante/usuario/admin
   * 
   * @method profile
   * 
   * @type Http GET
   * @route `/profile`
   */
  public function profile() {

    $session = $this->getSession();

    if (is_null($session))
			throw new UniversityMarketException("Não foi possível obter o profile");

    // Para model específica pelo tipo de sessão
    $profile_type = $this->getRequestSource();

    if ($profile_type == SESSION_TYPE_ADMIN) {

			$model = new ProfileUsuarioModel();

			// $model->token = $session->token;
			$model->nome = $session->usuario->nome ?? null;
			$model->email = $session->usuario->email ?? null;
			$model->usuarioId = $session->usuario_id;

			return $this->response($model);
    }

		if ($profile_type == SESSION_TYPE_ESTUDANTE) {

			$model = new ProfileEstudanteModel();
	
			// $model->token = $session->token;
			$model->nome = $session->estudante->nome ?? null;
			$model->email = $session->estudante->email ?? null;
			$model->estudanteId = $session->estudante_id;

			return $this->response($model);
		}

		throw new UniversityMarketException("Ocorreu um erro na obtenção do profile");
	}

}