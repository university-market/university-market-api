<?php

namespace App\Http\Controllers\Account\Models\RecuperacaoSenha;

use App\Base\Exceptions\UniversityMarketException;

class AlteracaoSenhaModel {

    public $senha;
    public $email;
    public $token;

    public function validar() {

        if (is_null($this->senha) || empty($this->senha)) {

            throw new UniversityMarketException("É obrigatório informar uma senha");
        }

        if (is_null($this->email) || empty($this->email)) {

            throw new UniversityMarketException("É obrigatório informar seu e-mail");
        }

    }
}