<?php

namespace App\Http\Controllers\Forgotsenha\Models;

class ForgotModel {

    public $senha;
    public $confirmasenha;
    public $email;
    public $token;
    
    public function validar() {
        
        if (!$this->email && !$this->senha){
            throw new \Exception('Dados Incompletos para Recuperação de senha');
        }

        if(!$this->token){
            throw new \Exception('token não informado');
        }
    }
}