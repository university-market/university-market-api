<?php

namespace App\Base\Exceptions;

use Exception;

/**
 * @class `UniversityMarketException`
 * @description Classe padrão de exceção do projeto University Market
 */
class UniversityMarketException extends Exception {

    /**
     * @param string `$message` Mensagem a ser lançada na exceção
     * @param int `$code` Código da exceção
     */
    public function __construct($message = "", $code = 0) {

        parent::__construct($message, $code);
    }
}