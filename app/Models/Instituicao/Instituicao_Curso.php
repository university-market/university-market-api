<?php

namespace App\Models\Instituicao;

use \Illuminate\Database\Eloquent\Model;

use App\Models\Instituicao\Instituicao;
use App\Models\Curso\Curso;

class Instituicao_Curso extends Model{
    
    public $timestamps = false; // Nao registrar data/hora criacao/alteracao

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'instituicaoId' => 'integer',
        'cursoId' => 'integer'
    ];

    protected $table = 'Instituicao_Curso';

    protected $instituicaoId;
    protected $cursoId;

    // Entity Relationships

    /**
     * Obtem a Instituicao
     */
    public function instituicao()
    {
        return $this->hasOne(Instituicao::class, 'instituicaoId', 'instituicaoId');
    }

    /**
     * Obtem o Curso
     */
    public function curso()
    {
        return $this->hasOne(Curso::class, 'cursoId', 'cursoId');
    }
}
