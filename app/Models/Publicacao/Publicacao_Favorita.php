<?php

namespace App\Models\Publicacao;

use App\Base\Models\UniversityMarketModel;

use App\Models\Publicacao\Publicacao;


class Publicacao_Favorita extends UniversityMarketModel {
    
    // Nao registrar data/hora criacao/alteracao
    public $timestamps = false;

    protected $table = 'Publicacaoes_favoritas';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
         'publicacao_id' => 'integer',
         'estudante_id' => 'integer'
    ];

    protected $publicacao_id; // FK Publicacoes
    protected $estudante_id; // FK Estudantes

    // Foreign Key para entidade de Publicacao
    public function publicacao()
    {
        return $this->hasOne(Publicacao::class, 'publicacao_id', 'id');
    }

}
