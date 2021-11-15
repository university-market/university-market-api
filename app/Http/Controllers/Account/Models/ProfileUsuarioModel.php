<?php

namespace App\Http\Controllers\Account\Models;

use App\Http\Controllers\Account\Models\Base\ProfileBaseModel;

class ProfileUsuarioModel extends ProfileBaseModel {

    /**
     * Id do Usuário da sessão atual
     */
    public $usuarioId;
}