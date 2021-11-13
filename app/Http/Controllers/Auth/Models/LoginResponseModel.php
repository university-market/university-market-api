<?php

namespace App\Http\Controllers\Auth\Models;

use App\Base\Exceptions\UniversityMarketException;

class LoginResponseModel {

    /**
     * Token de autenticação enviado como resposta do login
     */
    public $token;

    function __construct($auth_token)
    {
        $this->token = $auth_token ?? throw new UniversityMarketException("Token de autenticação inválido");
    }
}