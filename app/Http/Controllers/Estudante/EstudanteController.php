<?php

namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Estudante\Models\EstudanteContatosModel;
use Illuminate\Http\Request;

// Base
use App\Base\Controllers\UniversityMarketController;
use App\Base\Exceptions\UniversityMarketException;

// Common
use Illuminate\Support\Facades\Hash;

// Entidades
use App\Models\Estudante\Estudante;

// Models de estudante utilizadas
use App\Http\Controllers\Estudante\Models\EstudanteDetalheModel;
use App\Http\Controllers\Estudante\Models\EstudanteCriacaoModel;
use App\Http\Controllers\Estudante\Models\EstudanteDadosModel;
use App\Models\Estudante\Contato;

class EstudanteController extends UniversityMarketController {

  public function obter($estudanteId) {

    if (!$estudanteId)
      throw new UniversityMarketException("Estudante não encontrado");

    $session = $this->getSession();
    
    if (is_null($session))
      return $this->unauthorized();

    $estudante = Estudante::find($estudanteId);

    if (\is_null($estudante))
      throw new UniversityMarketException("Estudante não encontrado");

    // Construir model de detalhes do estudante
    $model = new EstudanteDetalheModel();

    $model->estudanteId = $estudante->id;
    $model->nome = $estudante->nome;
    $model->email = $estudante->email;
    $model->dataNascimento = $estudante->data_nascimento;
    $model->pathFotoPerfil = $estudante->caminho_foto_perfil;
    $model->cursoNome = $estudante->curso->nome;
    $model->instituicaoRazaoSocial = $estudante->instituicao->razao_social;

    return $this->response($model);
  }

  public function obterDados($estudanteId) {

    if (!$estudanteId)
      throw new \Exception("Estudante não encontrado");

    $session = $this->getSession();
    
    if (!$session)
      return $this->unauthorized();

    $estudante = Estudante::find($estudanteId);

    if (\is_null($estudante))
      throw new \Exception("Estudante não encontrado");

    // Construir model de detalhes do estudante
    $model = new EstudanteDadosModel();

    $model->nome = $estudante->nome;
    $model->email = $estudante->email;
    $model->cursoNome = $estudante->curso->nome;

    return response()->json($model);
  }

  public function criar(Request $request) {

    $model = $this->cast($request, EstudanteCriacaoModel::class);

    $model->validar();

    $existente = $this->estudanteExistente($model->email);

    if ($existente !== false) {

      throw new \Exception("Estudante já possui cadastro em $existente");
    }

    $estudante = new Estudante();

    $estudante->nome = $model->nome;
    $estudante->email = $model->email;
    $estudante->senha = Hash::make($model->senha);
    $estudante->ativo = true;
    $estudante->caminho_foto_perfil = null;
    $estudante->data_nascimento = $model->dataNascimento;
    $estudante->curso_id = $model->cursoId;
    $estudante->instituicao_id = $model->instituicaoId;
    
    $estudante->save();
  }

  public function cadastrarContato(Request $request){
    
    $model = $this->cast($request, EstudanteContatosModel::class);

    // Validar informacoes construidas na model
    $model->validar();

    $session = $this->getSession();

    if (!$session)
        return $this->unauthorized();
  
    //Valida se o tipo de contato já está cadastrado
    $validacao = Contato::where('estudante_id',$session->estudante_id)
                            ->where('tipo_contato_id',$model->tipo_contato_id)
                            ->where('deleted',false)
                            ->get()->toArray();

    if($validacao)
      throw new \Exception("Tipo de contato já cadastrado, favor edita-lo!");
    
    $contato = new Contato;

    $contato->conteudo = $model->conteudo;
    $contato->tipo_contato_id = $model->tipo_contato_id;
    $contato->estudante_id = $session->estudante_id;

    $contato->save();

    $model = new EstudanteContatosModel();

    $model->id = $contato->id;
    $model->conteudo = $contato->conteudo;
    $model->tipo_contato_id = $contato->tipo_contato_id;
        
    return response()->json($model);
  }

  public function deletarContato($contatoId){

    $session = $this->getSession();

    if (!$session)
        return $this->unauthorized();
    
    $contato =  Contato::find($contatoId);
    
    if (\is_null($contato))
            throw new \Exception("Contato não encontrado");
    if ($contato->deleted)
            throw new \Exception("Contato já deletado");
    
    if($contato->estudante_id != $session->estudante_id)
      throw new \Exception("Você não pode deletar este contato");

    $contato->deleted = true;

    $contato->save();
    
    return response(null, 200);
  }


  public function editarContato(Request $request){

    $model = $this->cast($request, EstudanteContatosModel::class);

    $session = $this->getSession();

    if (!$session)
        return $this->unauthorized();
    
    $contato =  Contato::find($model->id);
    
    if (\is_null($contato))
            throw new \Exception("Contato não encontrado");

    if ($contato->deleted)
            throw new \Exception("Contato encontra-se deletado");

    if($contato->estudante_id != $session->estudante_id)
      throw new \Exception("Você não pode editar este contato");

    $contato->conteudo = $model->conteudo;

    $contato->save();
    
    return response(null, 200);
  }

  public function obterContatos($estudanteId){

    $session = $this->getSession();

      if (!$session)
          return $this->unauthorized();

      $contatos = Contato::where('estudante_id', $estudanteId)
                              ->where('deleted',false)
                              ->get()->toArray();
                              
      $model = $this->cast($contatos, EstudanteContatosModel::class);

      return response()->json($model);
  }

  /**
   * @param string $email E-mail do estudante (deve ser único na instituicao)
   * @param string $instituicaoId Id da intituicao de ensino
   */
  private function estudanteExistente($email) {

    $estudante = Estudante::with('instituicao')->where('email', $email)->first();

    if (is_null($estudante))
      return false;

    return $estudante->instituicao->razao_social;
  }

}