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
    

    public function validar() {
        
        if (!$request->email && $request->password && $request->name){
            throw new \Exception('Dados Incompletos!');
        }

        $results = DB::select('select * from Users where email = :email', ['email' => $request->email]);

        if (count($results) > 0){
            throw new \Exception('E-mail já cadastrado!');
        }

            if($this->validaCPF($request->cpf) != true){
                throw new \Exception('cpf invalido!');
            }

            
            if($this->validaremail($request->email) != true){
                throw new \Exception('email invalido!');
            }
    }
}