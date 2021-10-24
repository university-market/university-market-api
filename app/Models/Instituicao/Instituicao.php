<?php

namespace App\Models\Instituicao;

use \Illuminate\Database\Eloquent\Model;
use App\Models\Plano\Plano;

class Instituicao extends Model{
    
    // Registrar data/hora criacao/alteracao
    public $timestamps = true;

    protected $table = 'Instituicoes';
    // protected $primaryKey = 'id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ativa' => 'boolean',
        'approved_at' => 'datetime',
        // 'plano_id' => 'integer'
    ];

    protected $id; // PK
    protected $nome_fantasia;
    protected $razao_social;
    protected $cnpj;
    protected $email;
    protected $ativa;
    protected $approved_at;
    protected $created_at;
    protected $updated_at;
    protected $plano_id;

    // Entity Relationships

    /**
     * Obtem o Plano associado à Instituicao
     */
    public function plano()
    {
        return $this->hasOne(Plano::class);
    }
}
