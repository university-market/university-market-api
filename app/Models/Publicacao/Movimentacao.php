<?php

namespace App\Models\Publicacao;

use \Illuminate\Database\Eloquent\Model;

class Movimentacao extends Model
{

    // Registrar data/hora criacao/alteracao
    public $timestamps = true;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */

    protected $casts = [
        'id' => 'integer',
        'valor' => 'float',
        'tipo_movimentacao_id' => 'integer',
        'publicacao_id' => 'integer',
        'estudante_id' => 'integer',
    ];

    protected $table = 'movimentacoes';
    protected $primaryKey = 'id';

    protected $id; // PK
    protected $valor;
    protected $tipo_movimentacao_id; // FK tipos_movimentacoes
    protected $publicacao_id; // FK Publicacoes
    protected $estudante_id; // FK Estudante

    // Timestamps da entidade
    private $created_at;
    private $updated_at;
}
