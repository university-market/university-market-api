<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Usuario extends Model{

    public $timestamps = false; // Nao registrar data/hora criacao/alteracao
    protected $table = 'Users';
    protected $primaryKey = 'id';

    // Colunas da tabela
    protected $id;
    protected $name;
}