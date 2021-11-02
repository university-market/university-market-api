<?php

namespace App\Models\Instituicao;

use \Illuminate\Database\Eloquent\Model;

use App\Models\Instituicao\Instituicao;
use App\Models\Curso\Curso;

class Instituicao_Curso extends Model {
    
    // Nao registrar data/hora criacao/alteracao
    public $timestamps = false;

    protected $table = 'Instituicoes_Cursos';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // 'instituicao_id' => 'integer',
        // 'curso_id' => 'integer'
    ];

    protected $instituicao_id;
    protected $curso_id;

    // Entity Relationships

    /**
     * Obtem a Instituicao
     */
    public function instituicao()
    {
        return $this->hasOne(Instituicao::class, 'id', 'instituicao_id');
    }

    /**
     * Obtem o Curso
     */
    public function curso()
    {
        return $this->hasOne(Curso::class, 'id', 'curso_id');
    }
}
