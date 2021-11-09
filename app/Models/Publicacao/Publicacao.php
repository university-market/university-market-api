<?php

namespace App\Models\Publicacao;

use \Illuminate\Database\Eloquent\Model;

use App\Models\Curso\Curso;
use App\Models\Estudante\Estudante;

class Publicacao extends Model {
    
    // Nome da entidade no banco de dados
    protected $table = 'Publicacoes';

    // Registrar data/hora criacao/alteracao
    public $timestamps = true;

    // Type Casting para campos com tipos especiais (não string)
    protected $casts = [
        'valor' => 'double',
        'data_hora_finalizacao' => 'datetime',
        'deleted' => 'boolean'
    ];

    // Primary Key da entidade
    protected $id;
    protected $primaryKey = 'id';

    // Properties
    protected $titulo;
    protected $descricao;
    protected $especificacao_tecnica;
    protected $valor;
    protected $caminho_imagem;
    protected $data_hora_finalizacao;
    protected $deleted;

    // Timestamps da entidade
    private $created_at;
    private $updated_at;

    /**
     * @region Entity Relationships
     */

     // Foreign Key para entidade de Curso
    protected $curso_id;
    public function curso()
    {
        return $this->hasOne(Curso::class, 'id', 'curso_id');
    }

    // Foreign Key para entidade de Estudante
    protected $estudante_id;
    public function estudante()
    {
        return $this->hasOne(Estudante::class, 'id');
    }

    // Foreign Key para entidade de Estudante
    public function tags()
    {
        return $this->hasMany(Tag::class, 'publicacao_id');
    }

}
