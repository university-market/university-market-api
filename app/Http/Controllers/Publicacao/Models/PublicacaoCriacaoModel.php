<?php

namespace App\Http\Controllers\Publicacao\Models;

class PublicacaoCriacaoModel {

    public $publicacaoId;
    public $titulo;
    public $descricao;
    public $valor;
    public $tags;
    public $especificacoesTecnicas;
    public $pathImagem;

    public function validar() {
        
        if (is_null($this->titulo) || empty(trim($this->titulo)))
            throw new \Exception("O título da publicação é obrigatório");
        
        if (is_null($this->descricao) || empty(trim($this->descricao)))
            throw new \Exception("A descrição da publicação é obrigatório");

        if (is_null($this->valor))
            throw new \Exception("O valor é obrigatório");

        if ($this->especificacoesTecnicas == 'null' || 
            \is_null($this->especificacoesTecnicas) || 
            empty(trim($this->especificacoesTecnicas)))
            $this->especificacoesTecnicas = null;
        else
            $this->especificacoesTecnicas = trim($this->especificacoesTecnicas);

        if (\is_null($this->tags) || empty(trim($this->tags)))
            $this->tags = null;
        else
            $this->tags = trim($this->tags);
    }
}