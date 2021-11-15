<?php

namespace App\Http\Controllers\Usuario\Models;

use App\Base\Validators\PessoaValidator;
use App\Base\Exceptions\UniversityMarketException;

class CriacaoUsuarioModel {

    public $nome;
    public $email;
    public $cpf;
    public $senha;
    public $dataNascimento;
    public $instituicaoId;
    
    public function validar() {
        
        if (is_null($this->nome) || empty($this->nome)) {

            throw new UniversityMarketException("O nome é obrigatório");
        }

        if (is_null($this->email) || empty($this->email)) {

            throw new UniversityMarketException("O e-mail é obrigatório");
        }

        $this->validarEmail();

        if (is_null($this->cpf) || empty($this->cpf)) {

            throw new UniversityMarketException("O CPF é obrigatório");
        }

        $this->validarCpf();

        if (is_null($this->senha) || empty($this->senha)) {

            throw new UniversityMarketException("A senha é obrigatória");
        }

        if (is_null($this->dataNascimento) || empty($this->dataNascimento)) {

            throw new UniversityMarketException("A data de nascimento é obrigatória");
        }

        $this->validarMaioridadeCivil();

        if (is_null($this->instituicaoId) || empty($this->instituicaoId))
            throw new UniversityMarketException("O cadastro do usuário deve estar relacionado à uma instituição de ensino");
    }

    /**
     * Validar e-mail informado na model
     */
    private function validarEmail() {
        
        if (!PessoaValidator::validarEmail($$this->email))
            throw new UniversityMarketException("Endereço de e-mail inválido");
    }

    /**
     * Validar CPF informado na model
     */
    private function validarCpf() {
 
        if (!PessoaValidator::validarCpf($this->cpf))
            throw new UniversityMarketException("CPF informado não é válido");
    }

    /**
     * Validar idade informada na model
     */
    private function validarMaioridadeCivil() {

        if (!PessoaValidator::validarMaioridade($this->dataNascimento))
            throw new UniversityMarketException("É necessário ser maior de idade para se cadastrar no sistema");
    }
}