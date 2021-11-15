<?php

namespace App\Http\Controllers\Account\Models\RecuperacaoSenha;

class RecuperacaoSenhaModel {

    public $expirationTime; // Minutes
    public $existente = false;

    function __construct($expirationTime = null) {
        
        $this->expirationTime = $expirationTime;
    }
}