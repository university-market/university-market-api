<?php

namespace App\Http\Controllers\Curso;

use Illuminate\Http\Request;

// Base
use App\Base\Controllers\UniversityMarketController;
use App\Base\Exceptions\UniversityMarketException;

// Common
use App\Common\Datatype\KeyValuePair;

// Entidades
use App\Models\Curso\Curso;
use App\Models\Instituicao\Instituicao;
use App\Models\Instituicao\Instituicao_Curso;

// Models de curso utilizadas

class CursoController extends UniversityMarketController {

  /**
   * Listar cursos de determinada Instituição de Ensino
   * 
   * @method listarPorInstituicao
   * 
   * @type Http GET
   * @route `/{instituicaoId}/listar`
   */
  public function listarPorInstituicao($instituicaoId) {

    if (is_null($instituicaoId))
      throw new UniversityMarketException("Instituição não encontrada");

    $instituicao = Instituicao::find($instituicaoId);

    if (is_null($instituicao))
      throw new UniversityMarketException("Instituição não encontrada");

    $relations = Instituicao_Curso::with(['curso'])->where('instituicao_id', $instituicaoId)->get();

    $lista_cursos = array_map(function($e) {
      return $e->curso;
    }, $relations->all());

    return $this->response(
      $this->criarListaKeyValue($lista_cursos)
    );
  }

  /**
   * Listar todos os cursos disponiveis cadastrados no banco de dados
   * 
   * @method listarTodos
   * 
   * @type Http GET
   * @route `/all`
   */
  public function listarTodos() {

    $lista_cursos = Curso::all()->getDictionary();

    return $this->response(
      $this->criarListaKeyValue($lista_cursos)
    );
  }

  // Private methods

  /**
   * Criar listagem de cursos no formato KeyValuePair
   * 
   * @method criarListaKeyValue
   * @param array $lista_cursos Listagem de entidade Curso a ser convertida em listagem KeyValuePair
   */
  private function criarListaKeyValue($lista_cursos) {

    $listagem = [];

    foreach ($lista_cursos as $curso) {

      $keyValue = new KeyValuePair();

      $keyValue->key = $curso->id;
      $keyValue->value = $curso->nome;

      $listagem[] = $keyValue;
    }

    return $listagem;
  }
}