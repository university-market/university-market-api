<?php

namespace App\Base\Resource;

abstract class UniversityMarketResource {

    /**
     * @property $estudante Recurso do sistema do tipo Estudante
     */
    public static $estudante;

    /**
     * @property $usuario Recurso do sistema do tipo Usuario
     */
    public static $usuario;

    /**
     * @property $publicacao Recurso do sistema do tipo Publicação
     */
    public static $publicacao;

    /**
     * @property $instituicao Recurso do sistema do tipo Instituição
     */
    public static $instituicao;

    /**
     * @property $curso Recurso do sistema do tipo Curso
     */
    public static $curso;

    /**
     * @property $bloqueio Recurso do sistema do tipo Bloqueio
     */
    public static $bloqueio;

    /**
     * @property $movimentacao Recurso do sistema do tipo Movimentação
     */
    public static $movimentacao;
}