<?php

namespace App\Base\Resource;

abstract class UniversityMarketResource {

    /**
     * @property $session Recurso do sistema do tipo Session
     */
    public static $session = 1;

    /**
     * @property $estudante Recurso do sistema do tipo Estudante
     */
    public static $estudante = 2;

    /**
     * @property $usuario Recurso do sistema do tipo Usuario
     */
    public static $usuario = 3;

    /**
     * @property $publicacao Recurso do sistema do tipo Publicação
     */
    public static $publicacao = 4;

    /**
     * @property $instituicao Recurso do sistema do tipo Instituição
     */
    public static $instituicao = 5;

    /**
     * @property $curso Recurso do sistema do tipo Curso
     */
    public static $curso = 6;

    /**
     * @property $bloqueio Recurso do sistema do tipo Bloqueio
     */
    public static $bloqueio = 7;

    /**
     * @property $movimentacao Recurso do sistema do tipo Movimentação
     */
    public static $movimentacao = 8;

    /**
     * @property $movimentacao Recurso do sistema do tipo Movimentação
     */
    public static $endereco = 9;

    /*
     * @property $contato Recurso do sistema do tipo Movimentação
     */
    public static $contato = 10;
}