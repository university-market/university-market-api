<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Instituicao extends Model{
    
    public $timestamps = false; // Nao registrar data/hora criacao/alteracao

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'instituicaoId' => 'integer',
        'cadastroEtapa' => 'integer',
        'dataHoraCadastro' => 'datetime',
        'dataHoraAprovacao' => 'datetime',
        'ativa' => 'boolean'
    ];

    protected $table = 'Instituicao';
    protected $primaryKey = 'instituicaoId';

    protected $instituicaoId; // PK Instituicao
    protected $nomeFantasia;
    protected $razaoSocial;
    protected $nomeReduzido;
    protected $cnpj;
    protected $cpfRepresentante;
    protected $emailContato;
    protected $etapaCadastroId;
    protected $dataHoraCadastro;
    protected $dataHoraAprovacao;
    protected $ativa;
}