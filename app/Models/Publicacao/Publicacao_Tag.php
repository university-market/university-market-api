<?php

namespace App\Models\Publicacao;

use \Illuminate\Database\Eloquent\Model;

use App\Models\Publicacao\Publicacao;
use App\Models\Publicacao\Tag;

class Publicacao_Tag extends Model {
    
    // Nao registrar data/hora criacao/alteracao
    public $timestamps = false;

    protected $table = 'Publicacoes_Tags';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // 'tag_id' => 'integer',
        // 'publicacao_id' => 'integer'
    ];

    protected $tag_id;
    protected $publicacao_id;

    // Entity Relationships

    /**
     * Obtem a Tag
     */
    public function tag()
    {
        return $this->hasOne(Tag::class, 'id', 'tag_id');
    }

    /**
     * Obtem o Publicacao
     */
    public function publicacao()
    {
        return $this->hasOne(Publicacao::class, 'id', 'publicacao_id');
    }
}
