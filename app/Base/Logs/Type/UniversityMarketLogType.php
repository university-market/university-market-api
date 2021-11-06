<?php

namespace App\Base\Logs\Type;

abstract class UniversityMarketLogType {

    /**
     * @property string $criacao Tipo de log de criação de recurso
     */
    public static $criacao;

    /**
     * @property string $edicao Tipo de log de edição de recurso
     */
    public static $edicao;

    /**
     * @property string $exclusao Tipo de log de exclusão de recurso
     */
    public static $exclusao;
}