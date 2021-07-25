<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Sale extends Model{
    
    // Colunas da tabela
    
    public $publicacaoId;
    public $titulo;
    public $descricao;
    public $valor;
    public $pathImagem;
}