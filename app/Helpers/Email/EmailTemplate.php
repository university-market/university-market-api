<?php

namespace App\Helpers\Email;

abstract class EmailTemplate {

    /**
     * @property string $example Template de exemplo
     */
    public static $example = 'base';

    /**
     * @property string $recuperarSenha Template com link para recuperação de senha
     */
    public static $recuperarSenha = 'conta/recuperarsenha';

    /**
     * @property string $usuarioInstitucional Template informando a senha do usuário institucional criado
     */
    public static $usuarioInstitucional = 'conta/institucional';

}