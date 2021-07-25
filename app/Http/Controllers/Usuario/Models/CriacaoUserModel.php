<?php

namespace App\Http\Controllers\Usuario\Models;

class CriacaoUserModel {

    public $name;
    public $ra;
    public $telefone;
    public $cpf;
    public $senha;
    public $email;
    public $curso;
    public $dataNasc;
    
    
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

    public function validaCPF() {
 
        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $this->cpf );
         
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            throw new \Exception('Cpf invalido !');
        }
    
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            throw new \Exception('Cpf invalido !');
        }
    
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                throw new \Exception('Cpf invalido!');
            }
        }
        return true;
    
    }

    public function validar() {
        
        if (!$this->email && $this->password && $this->name){
            throw new \Exception('Dados Incompletos!');
        }

        if($this->validaCPF($this->cpf) != true){
            throw new \Exception('cpf invalido!');
        }

        
        if($this->validaremail($this->email) != true){
            throw new \Exception('email invalido!');
        }
    }
}