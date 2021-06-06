<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Publicacao extends Model{
    
    public $timestamps = false; // Nao registrar data/hora criacao/alteracao
    protected $table = 'Publicacao';
    protected $primaryKey = 'publicacaoId';

    protected $publicacaoId; // PK Publicacao
    // protected $usuarioId; // FK Tabela Usuario
    protected $titulo;
    protected $descricao;
    protected $valor;
    protected $pathImagem;
    protected $dataHoraCriacao;
    protected $dataHoraFinalizacao;
    protected $dataHoraExclusao;
}
