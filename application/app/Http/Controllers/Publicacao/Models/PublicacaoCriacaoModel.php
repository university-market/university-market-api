<?php

namespace App\Http\Controllers\Publicacao\Models;

class PublicacaoCriacaoModel {

    public $titulo;
    public $descricao;
    public $valor;
    public $tags;
    public $pathImagem;

    public function validar() {
        
        if (is_null($this->titulo) || empty(trim($this->titulo)))
            throw new \Exception("O título da publicação é obrigatório");
        
        if (is_null($this->descricao) || empty(trim($this->descricao)))
            throw new \Exception("A descrição da publicação é obrigatório");

        if (is_null($this->valor))
            throw new \Exception("O valor é obrigatório");
    }
}