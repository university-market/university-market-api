<?php

namespace App\Http\Controllers\Instituicao;

use Illuminate\Http\Request;

// Base
use App\Base\Controllers\UniversityMarketController;
use App\Base\Exceptions\UniversityMarketException;
use App\Base\Logs\Logger\UniversityMarketLogger;
use App\Base\Resource\UniversityMarketResource;

// Logs
use App\Base\Logs\Type\StdLogType;
use App\Common\Constants\UniversityMarketConstants;
// Common
use App\Common\Datatype\KeyValuePair;
use App\Helpers\Email\EmailHelper;
use App\Helpers\Email\EmailTemplate;
use App\Helpers\Token\TokenHelper;
// Entidades
use App\Models\Instituicao\Instituicao;

// Models utilizadas
use App\Http\Controllers\Usuario\Models\CriacaoUsuarioModel;
use App\Http\Controllers\Instituicao\Models\InstituicaoListaModel;
use App\Http\Controllers\Instituicao\Models\InstituicaoCriacaoModel;

// Repository
use App\Repositories\Usuario\UsuarioRepository;

class InstituicaoController extends UniversityMarketController {

  /**
	 * UsuarioRepository Instance - métodos comuns do módulo de Usuario
	 */
	protected $usuario_repository;

  /**
   * Valores constantes configurados para instituicao
   */
  private $constants;

	function __construct(UsuarioRepository $usuario_repository)
	{
		$this->usuario_repository = $usuario_repository;

    $this->constants = UniversityMarketConstants::instituicao();
	}

  //==================================================================================

  /**
   * Cadastrar Instituicao de ensino
   * 
   * @method cadastrar
   * @param Request $request Instância de requisição - Cast para `InstituicaoCriacaoModel`
   * 
   * @type Http POST
   * @route ``
   */
  public function cadastrar(Request $request) {

    $model = $this->cast($request, InstituicaoCriacaoModel::class);
    $model->validar();

    $instituicao = new Instituicao();

    $instituicao->nome_fantasia = $model->nomeFantasia;
    $instituicao->razao_social = $model->razaoSocial;
    $instituicao->cnpj = $model->cnpj;
    $instituicao->email = $model->email;
    $instituicao->ativa = false; // Cadastro deve iniciar desativado
    $instituicao->approved_at = null; // Somente quando aprovada
    $instituicao->plano_id = null; // Quando sistema de planos estiver implementado

    $instituicao->save();

    // Persistir log de criacao da instituicao
    UniversityMarketLogger::log(
      UniversityMarketResource::$instituicao,
      $instituicao->id,
      StdLogType::$criacao,
      "Instituição criada",
      null,
      null
    );

    // Criacao de usuario padrao para conta da universidade
    $model = $this->criarModelUsuarioInstituicao($instituicao);

    $usuario_id = $this->usuario_repository->createUsuario($model);
    
    // Persistir log de criacao de usuario institucional
    UniversityMarketLogger::log(
      UniversityMarketResource::$usuario,
      $usuario_id,
      StdLogType::$criacao,
      "Usuário criado - Conta Institucional",
      null,
      null
    );

    $payload = [
      'email' => $instituicao->email,
      'razaoSocial' => $instituicao->razao_social,
      'senha' => $model->senha
    ];

    // Envio de e-mail com senha da conta institucional
    EmailHelper::send(null, $payload, EmailTemplate::$usuarioInstitucional);

    // Persistir log de criacao de usuario institucional
    UniversityMarketLogger::log(
      UniversityMarketResource::$instituicao,
      $instituicao->id,
      StdLogType::$email,
      "E-mail enviado com senha da conta institucional",
      null,
      null
    );

    return $this->response();
  }

  /**
   * Ativar cadastro da Instituicao
   * 
   * @method ativar
   * @param int $instituicaoId Id da Instituicao a ser ativada
   * 
   * @type Http PUT
   * @route `/{instituicaoId}/ativar`
   */
  public function ativar($instituicaoId) {

    // Log de ativacao de instituicao

    return $this->alterarStatusAtiva($instituicaoId, true);
  }

  /**
   * Desativar cadastro da Instituicao
   * 
   * @method desativar
   * @param int $instituicaoId Id da Instituicao a ser desativada
   * 
   * @type Http PUT
   * @route `/{instituicaoId}/desativar`
   */
  public function desativar($instituicaoId) {

    // Log de desativacao de instituicao

    return $this->alterarStatusAtiva($instituicaoId, false);
  }

  /**
   * Aprovar cadastro da Instituicao
   * 
   * Dados devem ser validados na Receita Federal e pagamento inicial deve ser confirmado
   * 
   * @method aprovar
   * @param int $instituicaoId Id da Instituicao a ser aprovada
   * 
   * @type Http POST
   * @route `/{instituicaoId}/aprovar`
   */
  public function aprovar($instituicaoId) {

    $instituicao = Instituicao::find($instituicaoId);

    if (is_null($instituicao))
      throw new UniversityMarketException("Instituição não encontrada");

    if (!is_null($instituicao->approved_at))
      throw new UniversityMarketException("Essa instituição já teve o cadastro aprovado");

    $instituicao->approved_at = $this->now();
    $instituicao->save();

    // Log de aprovacao de cadastro de instituicao

    return $this->response();
  }

  /**
   * Listar todas as instituicoes cadastradas na plataforma
   * 
   * @method listarTodas
   * 
   * @type Http GET
   * @route `/buscar`
   */
  public function listarTodas() {

    $instituicoes = Instituicao::all()->getDictionary();

    $listaModels = [];

    foreach ($instituicoes as $instituicao) {

      $model = new InstituicaoListaModel();

      $model->instituicaoId = $instituicao->id;
      $model->razaoSocial = $instituicao->razao_social;
      $model->nomeFantasia = $instituicao->nome_fantasia;
      $model->cnpj = $instituicao->cnpj;
      $model->email = $instituicao->email;
      $model->dataHoraCadastro = $instituicao->created_at;
      $model->aprovada = !is_null($instituicao->approved_at);
      $model->ativa = $instituicao->ativa;

      $listaModels[] = $model;
    }
    
    return $this->response($listaModels);
  }

  /**
   * Listar insituicoes Disponiveis para cadastro de estudantes
   * 
   * @method listarDisponiveis
   * 
   * @type Http GET
   * @route `/buscar/disponiveis`
   */
  public function listarDisponiveis() {

    $instituicoes = Instituicao::getDisponiveis();

    $lista_instituicoes = [];

    foreach ($instituicoes as $e) {

      $element = new KeyValuePair();

      $element->key = $e->id;
      $element->value = $e->razao_social;

      $lista_instituicoes[] = $element;
    }
    
    return $lista_instituicoes;
  }

  // Private methods

  private function alterarStatusAtiva($instituicaoId, $novoStatus) {

    $instituicao = Instituicao::find($instituicaoId);

    if (is_null($instituicao))
      throw new UniversityMarketException("Instituição não encontrada");

    if (is_null($instituicao->approved_at))
      throw new UniversityMarketException("Cadastro da instituição ainda não foi aprovado");

    if ($instituicao->ativa == $novoStatus) {
      
      $currentStatus = $instituicao->ativa ? "ativa" : "desativada";
      throw new UniversityMarketException("A instituição já está registrada como $currentStatus");
    }

    $instituicao->ativa = $novoStatus;
    $instituicao->save();

    return $this->response();
  }

  private function criarModelUsuarioInstituicao($entity) {

    $model = new CriacaoUsuarioModel();

    $model->nome = $entity->razao_social;
    $model->email = $entity->email;
    $model->cpf = $this->constants['default_instituicao_user_cpf'];
    $model->senha = TokenHelper::generateRandomPassword(6);
    $model->dataNascimento = $this->now();
    $model->instituicaoId = $entity->id;

    return $model;

  }

}