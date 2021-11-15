<?php

namespace App\Http\Controllers\Usuario;

use Illuminate\Http\Request;

// Base
use App\Base\Controllers\UniversityMarketController;
use App\Base\Exceptions\UniversityMarketException;

// Entidades
use App\Models\Usuario\Usuario;

// Models
use App\Http\Controllers\Usuario\Models\CriacaoUsuarioModel;

// Repositories
use App\Repositories\Usuario\UsuarioRepository;

class UsuarioController extends UniversityMarketController {

    /**
	 * UsuarioRepository Instance - métodos comuns do módulo de Usuario
	 */
	protected $usuario_repository;

	function __construct(UsuarioRepository $usuario_repository)
	{
		$this->usuario_repository = $usuario_repository;
	}

	// =========================================================================================================

  /**
   * Criação de usuario
   * 
   * @method criar
   * @param Request $request Type Casting para `CriacaoUsuarioModel` - dados do usuario
   * 
   * @type Http POST
   * @route `/`
   */
  public function criar(Request $request) {

    $model = $this->cast($request, CriacaoUsuarioModel::class);

    $model->validar();

    // Criar usuário via repository
    $this->usuario_repository->createUsuario($model);
  }

}
