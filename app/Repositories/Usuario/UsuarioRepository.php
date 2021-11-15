<?php

namespace App\Repositories\Usuario;

// Base
use App\Base\Exceptions\UniversityMarketException;
use App\Common\Constants\UniversityMarketConstants;

// Models
use App\Models\Usuario\Usuario;
use App\Models\Session\BaseSession;

class UsuarioRepository {

    /**
     * Criação de um usuário dentro do sistema
     * 
     * @method createUsuario
     * @param \App\Http\Controllers\Usuario\Models\CriacaoUsuarioModel $model
     * 
     * @return void
     */
    public function createUsuario($model) {

        if (is_null($model))
            throw new UniversityMarketException("O modelo de usuário informado não é válido");

        $usuario = new Usuario();

        $usuario->nome = $model->nome;
        $usuario->email = $model->email;
        $usuario->senha = $model->senha;
        $usuario->cpf = $model->cpf;
        $usuario->ativo = false; // Cadastro do usuario deve iniciar inativo
        $usuario->data_nascimento = $model->dataNascimento;

        $usuario->save();
    }

}