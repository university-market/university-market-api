<?php

namespace App\Http\Controllers\Auth\Models;

class RecuperacaoSenhaEstudanteModel {

    public $expirationTime; // Minutes
    public $existente = false;

    function __construct($expirationTime = null) {
        
        $this->expirationTime = $expirationTime;
    }
}