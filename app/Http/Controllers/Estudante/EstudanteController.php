<?php

namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Base\UniversityMarketController;
use App\Http\Controllers\Estudante\Models\EstudanteContatosModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Models de publicacao utilizadas
use App\Models\Estudante\Estudante;
use App\Http\Controllers\Estudante\Models\EstudanteDetalheModel;
use App\Http\Controllers\Estudante\Models\EstudanteCriacaoModel;
use App\Http\Controllers\Estudante\Models\EstudanteDadosModel;
use App\Models\Estudante\Bloqueios;
use App\Models\Estudante\Contato;

class EstudanteController extends UniversityMarketController {

  public function obter($estudanteId) {

    if (!$estudanteId)
      throw new \Exception("Estudante não encontrado");

    $session = $this->getSession();
    
    if (!$session)
      return $this->unauthorized();

    $estudante = Estudante::find($estudanteId);

    if (\is_null($estudante))
      throw new \Exception("Estudante não encontrado");

    // Construir model de detalhes do estudante
    $model = new EstudanteDetalheModel();

    $model->estudanteId = $estudante->estudanteId;
    $model->nome = $estudante->nome;
    $model->email = $estudante->email;
    $model->telefone = $estudante->telefone;
    $model->dataNascimento = $estudante->dataNascimento;
    $model->pathFotoPerfil = $estudante->pathFotoPerfil;
    $model->cursoNome = $estudante->curso->nome;
    $model->instituicaoRazaoSocial = $estudante->instituicao->razaoSocial;

    return response()->json($model);
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
    $estudante->telefone = $model->telefone;
    $estudante->dataNascimento = $model->dataNascimento;
    $estudante->hashSenha = Hash::make($model->senha);
    $estudante->pathFotoPerfil = null;
    $estudante->ativo = true;
    $estudante->dataHoraCadastro = date($this->dateTimeFormat);
    $estudante->cursoId = $model->cursoId;
    $estudante->instituicaoId = $model->instituicaoId;
    
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
    $validacao = Contato::where('estudante_id',$model->estudante_id)
                            ->where('tipo_contato_id',$model->tipo_contato_id)
                            ->get()->toArray();

    if($validacao)
      throw new \Exception("Tipo de contato já cadastrado, favor edita-lo!");
    
    $contato = new Contato;

    $contato->conteudo = $model->conteudo;
    $contato->tipo_contato_id = $model->tipo_contato_id;
    $contato->estudante_id = $model->estudante_id;

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

    $estudante = Estudante::where('email', $email)->first();

    if (is_null($estudante))
      return false;

    return $estudante->instituicao->razaoSocial;
  }

  private function estudantebloqueado($estudante_id) {

    $estudante = Bloqueios::where('estudante_id', $estudante_id)->first();

    if (is_null($estudante))
      return false;

    return $estudante;
  }


  public function bloquear(Request $request) {

    $model = $this->cast($request, EstudantebloqueiModel::class);

    $existente = $this->estudanteExistente($model->email);

    if ($existente !== false) {
      throw new \Exception("Estudante já está bloqueio em $existente");
    }

    $bloqueio = new Bloqueios();

    $bloqueio->estudanteId = $model->estudanteId;
    $bloqueio->motivo = $model->motivo;
    $bloqueio->created_at = $model->created_at;
    $bloqueio->finished_at = $model->finished_at;

    $bloqueio->save();

  }

}