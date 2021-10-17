<?php

namespace App\Helpers\Email;

abstract class EmailTemplateIdentifier {

    /**
     * @property string $example Template de exemplo
     */
    public static $example = 'base';

    /**
     * @property string $recuperarSenha Template com link para recuperação de senha
     */
    public static $recuperarSenha = 'conta/recuperarsenha';

}