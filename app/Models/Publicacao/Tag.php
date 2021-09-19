<?php

namespace App\Models\Publicacao;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

    public $timestamps = false; // Nao registrar data/hora criacao/alteracao

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tagId' => 'integer'
    ];

    protected $table = 'Tag';
    protected $primaryKey = 'tagId';

    protected $tagId; // PK Tag
    protected $conteudo;
}