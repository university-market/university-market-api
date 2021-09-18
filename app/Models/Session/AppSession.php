<?php

namespace App\Models\Session;

use App\Models\Session\BaseSession;
use App\Models\Estudante\Estudante;

class AppSession extends BaseSession {

    protected $table = 'AppSession';

    protected $estudanteId;

    // Entity Relationships

    /**
     * Obtem o Estudante associado à Sessao
     */
    public function estudante()
    {
        return $this->hasOne(Estudante::class, 'estudanteId', 'estudanteId');
    }
}
