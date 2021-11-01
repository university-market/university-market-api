<?php

namespace App\Http\Controllers\Estudante\Models;

// Base
use App\Base\Exceptions\UniversityMarketException;

use DateTime;

class EstudanteCriacaoModel {

  public $nome;
  public $email;
  public $telefone;
  public $dataNascimento;
  public $senha;
  public $cursoId;
  public $instituicaoId;

  public function validar() {

    $passwordMinLength = 6;

    if (is_null($this->nome) || empty(trim($this->nome)))
      throw new UniversityMarketException("O nome é obrigatório");

    if (is_null($this->email) || empty(trim($this->email)))
      throw new UniversityMarketException("O e-mail é obrigatório");

    if (is_null($this->dataNascimento) || empty(trim($this->dataNascimento))) {

      throw new UniversityMarketException("A data de nascimento é obrigatória");
    }
    elseif ($this->isMenorIdade($this->dataNascimento)) {

      throw new UniversityMarketException("É necessário ser maior de idade para realizar seu cadastro");
    }

    if (is_null($this->senha) || empty(trim($this->senha))) {

      throw new UniversityMarketException("A senha é obrigatória");
    }
    elseif (strlen(trim($this->senha)) < $passwordMinLength) {

      throw new UniversityMarketException("O tamanho mínimo para a senha é de $passwordMinLength caracteres");
    }

    if (is_null($this->cursoId) || empty(trim($this->cursoId)))
      throw new UniversityMarketException("É obrigatório informar o curso");

    if (is_null($this->instituicaoId) || empty($this->instituicaoId))
      throw new UniversityMarketException("É obrigatório informar a instituição de ensino");
  }

  private function isMenorIdade($dataNascimento) {

    $today = new DateTime(date('Y-m-d'));
    $initial_date = new DateTime(date($dataNascimento));

    return $initial_date->diff($today)->y < 18;
  }
}