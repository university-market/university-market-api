<?php

namespace App\Http\Controllers\Publicacao\Models;

class PublicacaoCriacaoModel {

    public $publicacaoId;
    public $titulo;
    public $descricao;
    public $valor;
    public $tags;
    public $detalhesTecnicos;
    public $pathImagem;

    public function validar() {
        
        if (is_null($this->titulo) || empty(trim($this->titulo)))
            throw new \Exception("O título da publicação é obrigatório");
        
        if (is_null($this->descricao) || empty(trim($this->descricao)))
            throw new \Exception("A descrição da publicação é obrigatório");

        if (is_null($this->valor))
            throw new \Exception("O valor é obrigatório");

        if ($this->detalhesTecnicos == 'null' || \is_null($this->detalhesTecnicos) || empty(trim($this->detalhesTecnicos)))
            $this->detalhesTecnicos = null;
        else
            $this->detalhesTecnicos = trim($this->detalhesTecnicos);

        if (\is_null($this->tags) || empty(trim($this->tags)))
            $this->tags = null;
        else
            $this->tags = trim($this->tags);
    }
}