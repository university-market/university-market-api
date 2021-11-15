<?php

namespace App\Http\Controllers\Account\Models\Base;

abstract class ProfileBaseModel {

    /**
     * Token utilizado na autenticacao das requisicoes
     */
    // public $token;

    /**
     * Endereço de e-mail utilizado na criação da sessão do estudante/usuário
     */
    public $email;

    /**
     * Nome do estudante/usuário
     */
    public $nome;

}