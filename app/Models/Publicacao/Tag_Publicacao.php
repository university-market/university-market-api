<?php

namespace App\Models\Publicacao;

use \Illuminate\Database\Eloquent\Model;

use App\Models\Publicacao\Publicacao;
use App\Models\Publicacao\Tag;

class Instituicao_Curso extends Model {
    
    public $timestamps = false; // Nao registrar data/hora criacao/alteracao

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tagId' => 'integer',
        'publicacaoId' => 'integer'
    ];

    protected $table = 'Tag_Publicacao';

    protected $tagId;
    protected $publicacaoId;

    // Entity Relationships

    /**
     * Obtem a Tag
     */
    public function tag()
    {
        return $this->hasOne(Tag::class, 'tagId', 'tagId');
    }

    /**
     * Obtem o Publicacao
     */
    public function publicacao()
    {
        return $this->hasOne(Publicacao::class, 'publicacaoId', 'publicacaoId');
    }
}
