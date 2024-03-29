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

    /**
     * Método estático para gerar token de validação de redefinição de senha
     * @return string Token gerado
     */
    public static function generatePasswordResetToken() {

        $n = 16;

        $token = bin2hex(random_bytes($n));

        // return Hash::make($base);
        return $token;
    }

    /**
     * Método estático para gerar senha inicial da conta institucional
     * @param int $n Quantidade de caracteres desejados
     * @return string Senha gerada
     */
    public static function generateRandomPassword($n) {

        $pass = bin2hex(random_bytes($n));

        return strtoupper($pass);
    }
}