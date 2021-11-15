<?php

namespace App\Models\Session;

use App\Models\Session\BaseSession;
use App\Models\Estudante\Estudante;

class AppSession extends BaseSession {

    protected $table = 'App_Sessions';

    protected $estudante_id;
    // protected $usuario_id;

    // Entity Relationships

    /**
     * Obtem o Estudante associado à Sessao
     */
    public function estudante()
    {
        return $this->hasOne(Estudante::class, 'id', 'estudante_id');
    }

    /**
     * Obtem o Usuario associado à Sessao
     */
    // public function usuario()
    // {
    //     return $this->hasOne(Usuario::class);
    // }
}
