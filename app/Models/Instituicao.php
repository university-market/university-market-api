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
        'dataHoraCadastro' => 'datetime',
        'aprovada' => 'boolean',
        'ativa' => 'boolean',
        'planoId' => 'integer'
    ];

    protected $table = 'Instituicao';
    protected $primaryKey = 'instituicaoId';

    protected $instituicaoId; // PK Instituicao
    protected $nomeFantasia;
    protected $razaoSocial;
    protected $nomeReduzido;
    protected $cnpj;
    protected $email;
    protected $telefone;
    protected $dataHoraCadastro;
    protected $aprovada;
    protected $ativa;
    protected $planoId;

    // Entity Relationships

    /**
     * Obtem o Plano associado à Instituicao
     */
    public function plano()
    {
        return $this->hasOne(Plano::class, 'planoId', 'planoId');
    }
}
