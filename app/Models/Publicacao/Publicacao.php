<?php

namespace App\Models\Publicacao;

use \Illuminate\Database\Eloquent\Model;

class Publicacao extends Model {
    
    public $timestamps = false; // Nao registrar data/hora criacao/alteracao

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'publicacaoId' => 'integer',
        'valor' => 'double',
        'dataHoraCriacao' => 'datetime',
        'dataHoraFinalizacao' => 'datetime',
        'excluida' => 'boolean',
        'cursoId' => 'integer',
        'estudanteId' => 'integer'
    ];

    protected $table = 'Publicacao';
    protected $primaryKey = 'publicacaoId';

    protected $publicacaoId; // PK Publicacao
    protected $titulo;
    protected $descricao;
    protected $especificacoesTecnicas;
    protected $valor;
    protected $pathImagem;
    protected $dataHoraCriacao;
    protected $dataHoraFinalizacao;
    protected $excluida;
    protected $cursoId; // FK Tabela Curso
    protected $estudanteId; // FK Tabela Estudante

    // Entity Relationships

    /**
     * Obtem o Curso associado à Publicacao
     */
    public function curso()
    {
        return $this->hasOne(Curso::class, 'cursoId', 'cursoId');
    }

    /**
     * Obtem o Estudante associado à Publicacao
     */
    public function estudante()
    {
        return $this->hasOne(Estudante::class, 'estudanteId', 'estudanteId');
    }
}
