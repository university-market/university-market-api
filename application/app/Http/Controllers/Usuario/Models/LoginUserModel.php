<?php

namespace App\Http\Controllers\Usuario\Models;

class LoginUserModel {

    public $senha;
    public $email;
        
    public function validaremail(){
        
        // Remove os caracteres ilegais, caso tenha
        $email = filter_var($this->email, FILTER_SANITIZE_EMAIL);

        // Valida o e-mail
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            throw new \Exception('email invalido !');
        }
    }

    public function validar() {
        
        if (!$this->email && $this->senha){
            throw new \Exception('Dados Incompletos!');
        }

        if($this->validaremail($this->email) != true){
            throw new \Exception('email invalido!');
        }
    }
}