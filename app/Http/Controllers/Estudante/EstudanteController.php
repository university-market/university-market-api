<?php

namespace App\Http\Controllers\Estudante;

use Illuminate\Http\Request;

// Base
use App\Base\Controllers\UniversityMarketController;
use App\Base\Exceptions\UniversityMarketException;
use App\Base\Logs\Logger\UniversityMarketLogger;
use App\Base\Logs\Type\StdLogChange;
use App\Base\Logs\Type\StdLogType;
use App\Base\Resource\UniversityMarketResource;

// Entidades
use App\Models\Estudante\Contato;
use App\Models\Estudante\Endereco;
use App\Models\Estudante\Estudante;
use App\Models\Estudante\Bloqueios;

// Models de estudante utilizadas
use App\Http\Controllers\Estudante\Models\EstudanteDenunciaModel;
use App\Http\Controllers\Estudante\Models\EstudanteDadosModel;
use App\Http\Controllers\Estudante\Models\EstudanteDetalheModel;
use App\Http\Controllers\Estudante\Models\EstudanteCriacaoModel;
use App\Http\Controllers\Estudante\Models\EstudanteBloqueioModel;
use App\Http\Controllers\Estudante\Models\EstudanteContatosModel;
use App\Models\Estudante\Denuncia;
use App\Http\Controllers\Estudante\Models\EstudanteEnderecosModel;

class EstudanteController extends UniversityMarketController
{

  /**
   * Obter model de detalhes de um estudante
   * 
   * @method obter
   * @param int $estudanteId Id do estudante a ser obtido
   * 
   * @type Http GET
   * @route `/{estudanteId}`
   */
  public function obter($estudanteId) {

    if (!$estudanteId)
      throw new UniversityMarketException("Estudante não encontrado");

    $session = $this->getSession();

    if (is_null($session))
      return $this->unauthorized();

    $estudante = Estudante::find($estudanteId);

    if (is_null($estudante))
      throw new UniversityMarketException("Estudante não encontrado");

    // Construir model de detalhes do estudante
    $model = new EstudanteDetalheModel();

    $model->estudanteId = $estudante->id;
    $model->nome = $estudante->nome;
    $model->email = $estudante->email;
    $model->dataNascimento = $estudante->data_nascimento;
    $model->pathFotoPerfil = $estudante->caminho_foto_perfil;
    $model->cursoNome = $estudante->curso->nome;
    $model->instituicaoRazaoSocial = $estudante->instituicao->razao_social ?? null;

    return $this->response($model);
  }

  /**
   * Obter model de detalhes de um estudante
   * 
   * @deprecated Deve ser utilizado o método `profile` de `AccountController` - Nova estrutura Front-end não precisa mais desse método
   * 
   * @method obterDados
   * @param int $estudanteId Id do estudante que obtém os dados necessários
   * 
   * @type Http GET
   * @route `/dados/{estudanteId}`
   */
  public function obterDados($estudanteId) {

    if (!$estudanteId)
      throw new UniversityMarketException("Estudante não encontrado");

    $session = $this->getSession();

    if (!$session)
      return $this->unauthorized();

    $estudante = Estudante::find($estudanteId);

    if (is_null($estudante))
      throw new UniversityMarketException("Estudante não encontrado");

    // Construir model de detalhes do estudante
    $model = new EstudanteDadosModel();

    $model->nome = $estudante->nome;
    $model->email = $estudante->email;
    $model->cursoNome = $estudante->curso->nome;

    return $this->response($model);
  }

  /**
   * Criar estudante no banco de dados
   * 
   * @method criar
   * @param Request $request Instância de requisição a ser convertida em model de criação `EstudanteCriacaoModel`
   * 
   * @type Http POST
   * @route ``
   */
  public function criar(Request $request) {

    $model = $this->cast($request, EstudanteCriacaoModel::class);
    $model->validar();

    $estudante = new Estudante();

    $estudante->nome = $model->nome;
    $estudante->email = $model->email;
    $estudante->senha = $model->senha;
    $estudante->ativo = true;
    $estudante->caminho_foto_perfil = null;
    $estudante->data_nascimento = $model->dataNascimento;
    $estudante->curso_id = $model->cursoId;
    $estudante->instituicao_id = $model->instituicaoId;

    $estudante->save();

    // Persistir log de criacao do estudante
    UniversityMarketLogger::log(
      UniversityMarketResource::$estudante,
      $estudante->id,
      StdLogType::$criacao,
      "Estudante Criado",
      null,
      null
    );

  }

  public function cadastrarContato(Request $request)
  {
    $session = $this->getSession();

    if (!$session)
      return $this->unauthorized();

    $model = $this->cast($request, EstudanteContatosModel::class);

    // Validar informacoes construidas na model
    $model->validar();

    //Valida se o tipo de contato já está cadastrado
    $validacao = Contato::where('estudante_id', $session->estudante_id)
      ->where('tipo_contato_id', $model->tipo_contato_id)
      ->where('deleted', false)
      ->get()->toArray();

    if ($validacao)
      throw new \Exception("Tipo de contato já cadastrado, favor edita-lo!");

    $validacao = Contato::where('estudante_id', $model->estudante_id)
      ->where('tipo_contato_id', $model->tipo_contato_id)
      ->get()->toArray();

    if ($validacao)
      throw new UniversityMarketException("Tipo de contato já cadastrado, favor edita-lo!");

    $contato = new Contato;

    $contato->conteudo = $model->conteudo;
    $contato->tipo_contato_id = $model->tipo_contato_id;
    $contato->estudante_id = $session->estudante_id;

    $contato->save();

    $model = new EstudanteContatosModel();

    $model->id = $contato->id;
    $model->conteudo = $contato->conteudo;
    $model->tipo_contato_id = $contato->tipo_contato_id;

    // Persistir log de criacao de contato do estudante
    UniversityMarketLogger::log(
      UniversityMarketResource::$contato,
      $contato->id,
      StdLogType::$criacao,
      "Contato criado",
      $session->estudante_id,
      null
    );
    
    return response()->json($model);
  }

  public function deletarContato($contatoId)
  {

    $session = $this->getSession();

    if (!$session)
      return $this->unauthorized();

    $contato =  Contato::find($contatoId);

    if (\is_null($contato))
      throw new \Exception("Contato não encontrado");
    if ($contato->deleted)
      throw new \Exception("Contato já deletado");

    if ($contato->estudante_id != $session->estudante_id)
      throw new \Exception("Você não pode deletar este contato");

    $contato->deleted = true;

    $contato->save();

    return response(null, 200);
  }


  public function editarContato(Request $request)
  {

    $model = $this->cast($request, EstudanteContatosModel::class);

    $session = $this->getSession();

    if (!$session)
      return $this->unauthorized();

    $contato =  Contato::find($model->id);

    if (\is_null($contato))
      throw new \Exception("Contato não encontrado");

    if ($contato->deleted)
      throw new \Exception("Contato encontra-se deletado");

    if ($contato->estudante_id != $session->estudante_id)
      throw new \Exception("Você não pode editar este contato");

    $contato->conteudo = $model->conteudo;

    $contato->save();

    return response(null, 200);
  }

  public function obterContatos($estudanteId)
  {

    $session = $this->getSession();

    if (!$session)
      return $this->unauthorized();

    $contatos = Contato::where('estudante_id', $estudanteId)
      ->where('deleted', false)
      ->get()->toArray();

    $model = $this->cast($contatos, EstudanteContatosModel::class);

    return response()->json($model);
  }

  public function cadastrarEndereco(Request $request)
  {

    $session = $this->getSession();

    if (!$session)
      return $this->unauthorized();

    $model = $this->cast($request, EstudanteEnderecosModel::class);

    // Validar informacoes construidas na model
    $model->validar();

    $endereco = new Endereco;

    $endereco->estudante_id = $session->estudante_id;
    $endereco->logradouro = $model->rua;
    $endereco->complemento = $model->complemento;
    $endereco->cep = $model->cep;
    $endereco->numero = $model->numero;
    $endereco->municipio = $model->municipio;
    $endereco->bairro = $model->bairro;
    

    $endereco->save();

    $model = new EstudanteEnderecosModel();

    $model->id = $endereco->id;
    $model->rua = $endereco->logradouro;
    $model->complemento = $endereco->complemento;
    $model->cep = $endereco->cep;
    $model->numero = $endereco->numero;
    $model->municipio = $endereco->municipio;
    $model->bairro = $endereco->bairro;


    // Persistir log de criacao de publicacao
    UniversityMarketLogger::log(
      UniversityMarketResource::$endereco,
      $endereco->id,
      StdLogType::$criacao,
      "Endereço cradastrado",
      $session->estudante_id,
      null
    );

    return response()->json($model);    
  }

  public function obterEndereco($estudanteId = null)
  {

    $session = $this->getSession();

    if (!$session)
      return $this->unauthorized();

    $enderecos = Endereco::where('estudante_id', $estudanteId)
                          ->whereNull('deleted_at')
                          ->get();

    $list = [];

    foreach ($enderecos as $endereco) {

      $model = new EstudanteEnderecosModel();

      $model->id = $endereco->id;
      $model->estudanteId = $endereco->estudante_id;
      $model->rua = $endereco->logradouro;
      $model->numero = $endereco->numero;
      $model->cep = $endereco->cep;
      $model->complemento = $endereco->complemento;
      $model->municipio = $endereco->municipio;
      $model->bairro = $endereco->bairro;

      $list[] = $model;
    }

    return response()->json($list);
    $contatos = Contato::where('estudante_id', $estudanteId)
      ->where('deleted', false)
      ->get()->toArray();

    $model = $this->cast($contatos, EstudanteContatosModel::class);

    return $this->response($model);
  }

  public function deletarEndereco($enderecoId)
  {

    $session = $this->getSession();

    if (!$session)
      return $this->unauthorized();

    $endereco =  Endereco::find($enderecoId);

    if (is_null($endereco))
      throw new \Exception("Endereço não encontrado");

    if (!is_null($endereco->deleted_at))
      throw new \Exception("Endereço já deletado");

    if ($endereco->estudante_id != $session->estudante_id)
      throw new \Exception("Você não pode deletar este contato");

     $endereco->deleted_at = date($this->datetime_format);

     $endereco->save();

    // Persistir log de criacao de publicacao
    UniversityMarketLogger::log(
      UniversityMarketResource::$endereco,
      $endereco->id,
      StdLogType::$exclusao,
      "Endereço excluido",
      $session->estudante_id,
      null
    );

    return response(null, 200);
  }

  public function editarEndereco(Request $request){
    
    $session = $this->getSession();

    if (!$session)
      return $this->unauthorized();

    $model = $this->cast($request, EstudanteEnderecosModel::class);

    $model->validar();

    $endereco =  Endereco::find($model->id);

    if (\is_null($endereco))
      throw new \Exception("Endereço não encontrado");

    if (!is_null($endereco->deleted_at))
      throw new \Exception("Endereço encontra-se deletado");

    if ($endereco->estudante_id != $session->estudante_id)
      throw new \Exception("Você não pode editar este contato");

    $before = [
      'logradouro'=> $endereco->logradouro,
      'numero'=> $endereco->numero,
      'cep'=> $endereco->cep,
      'complemento'=> $endereco->complemento,
    ];



    $endereco->logradouro = $model->rua;
    $endereco->complemento = $model->complemento;
    $endereco->cep = $model->cep;
    $endereco->numero = $model->numero;
    $endereco->bairro = $model->bairro;
    $endereco->municipio = $model->municipio;

    $endereco->save();

    $after = [
      'logradouro'=> $endereco->logradouro,
      'numero'=> $endereco->numero,
      'cep'=> $endereco->cep,
      'complemento'=> $endereco->complemento,
    ];

    $changes = new StdLogChange();

    // Persistir log de criacao de publicacao
    UniversityMarketLogger::log(
      UniversityMarketResource::$endereco,
      $endereco->id,
      StdLogType::$edicao,
      "Endereço editado",
      $session->estudante_id,
      $changes->setBeforeState($before)->setAfterState($after)->serializeChanges()
    );

    return response(null, 200);

  }

  public function estudanteBloqueado($estudante_id)
  {

    $estudante = Bloqueios::where('estudante_id', $estudante_id)->first();

    if (is_null($estudante))
      return false;

    return $estudante;
  }


  public function bloquear(Request $request)
  {

    $model = $this->cast($request, EstudanteBloqueioModel::class);


    $session = $this->getSession();

    if (!$session)
        return $this->unauthorized();

    $existente = $this->estudanteBloqueado($model->estudante_id);

    if ($existente) {
      throw new \Exception("Estudante já está bloqueado");
    }

    $bloqueio = new Bloqueios();

    $bloqueio->estudante_id = $model->estudante_id;
    $bloqueio->motivo = $model->motivo;
    $bloqueio->finished_at = $model->finished_at;

    $bloqueio->save();

    // Log de criacao
  }

  public function denunciar(Request $request) {

    $model = $this->cast($request, EstudanteDenunciaModel::class);   
    
    $model->validar(); 

    $session = $this->getSession();

    if (!$session)
        return $this->unauthorized();

    $denuncia = new Denuncia();

    $denuncia->descricao = $model->descricao;
    $denuncia->estudante_id_autor = $model->estudante_id_autor;
    $denuncia->estudante_id_denunciado = $model->estudante_id_denunciado;
    $denuncia->movimentacao_id = $model->movimentacao_id;
  
    $denuncia->save();

  }

  public function obterDenuncias($estudanteId){

    $session = $this->getSession();

      if (!$session)
          return $this->unauthorized();

      $denuncias = Denuncia::where('estudante_id_denunciado', $estudanteId)->get();
      
      $list = [];

      foreach ($denuncias as $denuncia) {

        $model = new EstudanteDenunciaModel();
        $model->descricao = $denuncia->descricao;
        $model->estudante_id_autor = $denuncia->estudante_id_autor;
        $model->estudante_id_denunciado = $denuncia->estudante_id_denunciado;
        $model->movimentacao_id = $denuncia->movimentacao_id;

        $list[]= $model;
      }

      return $this->response($list);
  }

  


}

