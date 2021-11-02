<?php

namespace App\Models\Publicacao;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

    // Nao registrar data/hora criacao/alteracao
    public $timestamps = false;

    // protected $table = 'Tags';
    // protected $primaryKey = 'id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // 'id' => 'integer'
    ];

    protected $id; // PK
    protected $conteudo;
}