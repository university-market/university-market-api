<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class User extends Model{
    
    public $timestamps = false; // Nao registrar data/hora criacao/alteracao
    protected $table = 'Users';
    protected $primaryKey = 'id';

    // Colunas da tabela
    
    protected $id;
    protected $name;
    protected $ra;
    protected $telefone;
    protected $nivel_acesso;
    protected $ultimo_login;
    protected $cpf;
    protected $senha;
    protected $email;
    protected $date;
    protected $bloqued;
    protected $curso;
    protected $token;
    protected $excluido;
    
}
