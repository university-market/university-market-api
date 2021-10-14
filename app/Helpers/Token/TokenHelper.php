<?php

namespace App\Helpers\Token;

use Illuminate\Support\Facades\Hash;

abstract class TokenHelper {

    /**
     * Método estático para gerar um token de sessão para usuário/estudante
     * @return string Token gerado
     */
    public static function generateSessionToken() {

        $n = 18;

        $token = bin2hex(random_bytes($n));

        // return Hash::make($base);
        return $token;
    }
}