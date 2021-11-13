<?php

namespace App\Http\Controllers\Auth\Models;

use App\Base\Exceptions\UniversityMarketException;

class LoginResponseModel {

    public $token;

    function __construct($auth_token)
    {
        $this->auth_token = $auth_token ?? throw new UniversityMarketException("Token de autenticação inválido");
    }
}