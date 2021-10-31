<?php

namespace App\Models\Publicacao;

use \Illuminate\Database\Eloquent\Model;

use App\Models\Curso\Curso;
use App\Models\Estudante\Estudante;

class Publicacao extends Model {
    
    // Registrar data/hora criacao/alteracao
    public $timestamps = true;

    protected $table = 'Publicacoes';
    // protected $primaryKey = 'id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'titulo' => 'string',
        'descricao' => 'string',
        'especificacao_tecnica' => 'string',
        'valor' => 'double',
        'caminho_imagem' => 'string',
        'data_hora_finalizacao' => 'datetime',
        'deleted' => 'boolean'
    ];

    protected $id; // PK
    protected $titulo;
    protected $descricao;
    protected $especificacao_tecnica;
    protected $valor;
    protected $caminho_imagem;
    protected $data_hora_finalizacao;
    protected $deleted;
    private $created_at;
    private $updated_at;

    protected $curso_id; // FK Curso
    protected $estudante_id; // FK Estudante

    // Entity Relationships

    /**
     * Obtem o Curso associado à Publicacao
     */
    public function curso()
    {
        return $this->hasOne(Curso::class, 'id', 'curso_id');
    }

    /**
     * Obtem o Estudante associado à Publicacao
     */
    public function estudante()
    {
        return $this->hasOne(Estudante::class, 'id');
    }
}
