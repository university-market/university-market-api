<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;

// Base
use App\Base\Controllers\UniversityMarketController;
use App\Base\Exceptions\UniversityMarketException;

// Helpers
use App\Helpers\Token\TokenHelper;
use App\Helpers\Email\EmailHelper;
use App\Helpers\Email\EmailTemplate;

// Common
use App\Common\Constants\UniversityMarketConstants;
use Illuminate\Support\Facades\Hash;

// Models utilizadas
use App\Http\Controllers\Account\Models\ProfileUsuarioModel;
use App\Http\Controllers\Account\Models\ProfileEstudanteModel;
use App\Http\Controllers\Account\Models\RecuperacaoSenha\RecuperacaoSenhaModel;
use App\Http\Controllers\Account\Models\RecuperacaoSenha\AlteracaoSenhaModel;

// Entidades
use App\Models\Estudante\Estudante;
use App\Models\Account\RecuperacaoSenha;

// Repositories
use App\Repositories\Auth\AuthRepository;

class AccountController extends UniversityMarketController
{

	/**
	 * AuthRepository Instance - métodos comuns do módulo de autenticação
	 */
	protected $auth_repository;

	/**
	 * @property $recuperacao_senha_const Configurações constante definidas para módulo de Conta
	 */
	protected $recuperacao_senha_const;

	function __construct(AuthRepository $auth_repository)
	{
		$this->auth_repository = $auth_repository;
		$this->recuperacao_senha_const = UniversityMarketConstants::recuperacao_senha();
	}

	// =========================================================================================================
	
	/**
	 * Obter model de perfil - Conta estudante/usuario/admin
	 * 
	 * @method profile
	 * 
	 * @type Http GET
	 * @route `/profile`
	 */
	public function profile()
	{

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

	/**
	 * 
	 * 
	 * Recuperação de senha
	 * 
	 * 
	 */

	/**
	 * Criar solicitação de senha
	 * 
	 * @method solicitarRecuperacaoSenha
	 * @param Request $request Requisição recebida - Obter somente endereço de e-mail
	 * 
	 * @type Http POST
	 * @route `/recuperacaosenha/solicitar`
	 */
	public function solicitarRecuperacaoSenha(Request $request) {

		$email = $request->only('email')['email'];

		if (is_null($email) || empty(trim($email)))
			throw new UniversityMarketException("E-mail informado inválido");

		$email = trim($email);

		$origem_solicitacao = $this->getRequestSource();

		$solicitante = null;

		switch ($origem_solicitacao) {

			case SESSION_TYPE_ADMIN:
				$solicitante = null; // Buscar por Usuario
				break;

			case SESSION_TYPE_ESTUDANTE:
				$solicitante = Estudante::findByEmail($email);
				break;
		}

		// Validar existencia e autenticidade do solicitante
		if (is_null($solicitante) || !$solicitante->ativo)
			throw new UniversityMarketException("Não foi possível processar sua solicitação. E-mail inválido");

		
		$activatedSession = $this->auth_repository->getActivatedSession($solicitante->id, $origem_solicitacao);

		// Validar existencia de session do solicitante
		if (!is_null($activatedSession)) {

			// Realizar logout do solicitante
			$this->auth_repository->destroySession($activatedSession->id);
			// throw new UniversityMarketException("Existe uma sessão ativa neste endereço de e-mail");
		}

		$solicitante_field = 'estudante_id';

		if ($origem_solicitacao == SESSION_TYPE_ADMIN)
			$solicitante_field = 'usuario_id';

		$solicitacaoExistente = RecuperacaoSenha::where($solicitante_field, $solicitante->id)
			->where('completa', false)
			->where('expirada', false)
			->first();

		if (!is_null($solicitacaoExistente)) {

			if ($solicitacaoExistente->expiration_at < time()) {

				$solicitacaoExistente->expirada = true;
				$solicitacaoExistente->save();
			} else {

				$model = new RecuperacaoSenhaModel();

				$expirationTimeInMinutes = ($solicitacaoExistente->expiration_at - time()) / 60;

				$model->expirationTime = intval(ceil($expirationTimeInMinutes)); // In minutes
				$model->existente = true;

				return $this->response($model);
			}
		}

		$token = TokenHelper::generatePasswordResetToken();

		$expirationTime = $this->recuperacao_senha_const['token_expiration_time'];
		$solicitacaoDate = $this->now();

		$primeiroNome = explode(' ', $solicitante->nome)[0];

		$payload = [
			'email' => $email,
			'estudanteNome' => $primeiroNome,
			'token' => $token,
			'expirationTime' => $expirationTime,
			'requestDate' => $solicitacaoDate
		];

		// Enviar e-mail
		EmailHelper::send(null, $payload, EmailTemplate::$recuperarSenha);

		// Persistir solicitação de recuperação de senha
		$recuperacao = new RecuperacaoSenha();

		$recuperacao->token = $token;
		$recuperacao->email = $email;
		$recuperacao->completa = false;
		$recuperacao->expirada = false;
		$recuperacao->expiration_at = time() + $expirationTime * 60; // In seconds
		$recuperacao->estudante_id = $origem_solicitacao == SESSION_TYPE_ESTUDANTE ? $solicitante->id : null;
		// $recuperacao->usuario_id = $origem_solicitacao == SESSION_TYPE_ADMIN ? $solicitante->id : null;

		$recuperacao->save();

		return $this->response(new RecuperacaoSenhaModel($expirationTime));
	}

	/**
	 * Validar token de recuperação de senha - enviado via link no email do solicitante
	 * 
	 * @method validarTokenRecuperacaoSenha
	 * @param string $token Token recebido no link de e-mail enviado na solicitação
	 * @param boolean $obter Parâmetro interno para obter a solicitação ao invés de apenas validar
	 * 
	 * @type Http GET
	 * @route `/recuperacaosenha/validar/token/{token}`
	 */
	public function validarTokenRecuperacaoSenha($token, $obter = false) {

		$solicitacao = RecuperacaoSenha::with(['estudante'])
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

				throw new UniversityMarketException("Link expirado. Uma nova solicitação deve ser realizada");
			}

			if ($obter)
				return $solicitacao;

			return $this->response(true);
		}

		if ($obter)
			return null;

		throw new UniversityMarketException("Não foi possível encontrar esta página");
	}

	/**
	 * Validar e-mail de recuperação de senha - Para garantir que não houve vazamento do link para alguém desconhecido
	 * 
	 * @method validarEmailRecuperacaoSenha
	 * @param Request $request Instancia da requisição - Obter somente e-mail
	 * @param boolean $privateGet = Parâmetro interno para obter a solicitação
	 * 
	 * @type Http GET
	 * @route `/recuperacaosenha/validar/email`
	 */
	public function validarEmailRecuperacaoSenha(Request $request, $privateGet = false) {

		$data = $request->only(['email', 'token']);

		$solicitante = null;

		switch ($this->getRequestSource()) {

			case SESSION_TYPE_ADMIN:
				$solicitante = null; // Obter usuario
				break;

			case SESSION_TYPE_ESTUDANTE:
				$solicitante = Estudante::findByEmail($data['email']);
				break;
		}

		if (is_null($solicitante) || !$solicitante->ativo)
			throw new UniversityMarketException("Não há solicitação de redefinição de senha ativa");

		// Obter solicitacao via token
		$solicitacao = $this->validarTokenRecuperacaoSenha($data['token'], true);

		if (is_null($solicitacao)) {

			if ($privateGet)
				return null;

			throw new UniversityMarketException("Não há solicitação de redefinição de senha ativa");
		}

		$id_solicitante_recuperacao = $solicitacao->estudante_id ?? $solicitacao->usuario_id ?? null;
		$is_valid = false;

		if ($solicitante->id == $id_solicitante_recuperacao) {

			$is_valid = true;
		}

		return $privateGet ? $solicitacao : $this->response($is_valid);
	}

	/**
	 * Realizar alteração da senha do solicitante
	 * 
	 * @method alterarSenha
	 * @param Request $request Instancia da requisição - Cast para `AlteracaoSenhaModel`
	 * 
	 * @type Http PUT
	 * @route `/recuperacaosenha/alterar`
	 */
	public function alterarSenha(Request $request) {

		$model = $this->cast($request, AlteracaoSenhaModel::class);
		$model->validar();

		$solicitacao = $this->validarEmailRecuperacaoSenha($request, true);

		if (is_null($solicitacao))
			throw new UniversityMarketException("Não há mais uma solicitação ativa para este e-mail");

		$tolerancia = $this->recuperacao_senha_const['tolerancia_expiration_time'];
		$maxTime = $solicitacao->expiration_at + $tolerancia * 60; // Tolerância de X minutos além do tempo de expiração

		if ($maxTime < time())
			throw new UniversityMarketException("Esta solicitação expirou. Uma nova solicitação é necessária");

		$solicitante = null;

		switch ($this->getRequestSource()) {

			case SESSION_TYPE_ADMIN:
				$solicitante = null; // Obter Usuario
				break;

			case SESSION_TYPE_ESTUDANTE:
				$solicitante = Estudante::findByEmail($model->email);
				break;
		}

		if (is_null($solicitante) || !$solicitante->ativo)
			throw new UniversityMarketException("Usuário não localizado");

		if (Hash::check($model->senha, $solicitante->senha))
			throw new UniversityMarketException("A nova senha não pode ser igual à anterior");

		// Salvar nova senha
		$solicitante->senha = $model->senha;
		$solicitante->save();

		// Finalizar a solicitação de redefinição
		$solicitacao->completa = true;
		$solicitacao->save();

		return $this->response();
	}
}
