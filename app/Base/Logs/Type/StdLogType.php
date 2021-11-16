<?php

namespace App\Base\Logs\Type;

abstract class StdLogType {

    /**
     * @property string $consulta Tipo de log de consulta à um recurso
     */
    public static $consulta = 1;

    /**
     * @property string $criacao Tipo de log de criação de recurso
     */
    public static $criacao = 2;

    /**
     * @property string $edicao Tipo de log de edição de recurso
     */
    public static $edicao = 3;

    /**
     * @property string $exclusao Tipo de log de exclusão de recurso
     */
    public static $exclusao = 4;

    /**
     * @property string $email Tipo de log de envio de e-mail
     */
    public static $email = 4;
}