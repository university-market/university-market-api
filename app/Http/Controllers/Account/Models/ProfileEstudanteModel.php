<?php

namespace App\Http\Controllers\Account\Models;

use App\Http\Controllers\Account\Models\Base\ProfileBaseModel;

class ProfileEstudanteModel extends ProfileBaseModel {

    /**
     * Id do Estudante da sessão atual
     */
    public $estudanteId;

    /**
     * Nome do Curso do Estudante da sessão atual
     */
    public $cursoNome;

}