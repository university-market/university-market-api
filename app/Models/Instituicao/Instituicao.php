<?php

namespace App\Models\Instituicao;

// Base
use App\Base\Models\UniversityMarketModel;
use App\Base\Exceptions\UniversityMarketException;

// Models
use App\Models\Plano\Plano;

class Instituicao extends UniversityMarketModel {
    
    // Nome da entidade no banco de dados
    protected $table = 'Estudantes';

    // Registrar data/hora criacao/alteracao
    public $timestamps = true;

    
    // Type Casting para campos com tipos especiais (não string)
    protected $casts = [
        'ativa' => 'boolean',
        'approved_at' => 'datetime'
    ];
    
    // Primary key da entidade
    protected $id;
    protected $primaryKey = 'id';

    // Properties
    protected $nome_fantasia;
    protected $razao_social;
    protected $cnpj;
    protected $email;
    protected $ativa;
    protected $plano_id;
    
    // Timestamps da entidade
    private $created_at;
    private $updated_at;
    protected $approved_at;

    /**
     * @region Entity Relationships
     */

    // Foreign Key para entidade de Plano
    public function plano()
    {
        return $this->hasOne(Plano::class);
    }

    /**
     * @region Entity Acessors and Mutators
     */
    
    // Setter para CNPJ
    public function setCnpjAttribute($value) {

        $exists = self::exists($value, null);

        if ($exists)
            throw new UniversityMarketException("O CNPJ informado já se encontra cadastrado");

        $this->attributes['cnpj'] = $value;
    }

    // Setter para Razao_Social
    public function setRazaoSocialAttribute($value) {

        $exists = self::exists(null, $value);

        if ($exists)
            throw new UniversityMarketException("A Razão Social informada já se encontra cadastrada");

        $this->attributes['razao_social'] = $value;
    }

    /**
     * @region Queryable methods
     */

    /**
     * Validar existencia da instituicao de ensino por CNPJ ou RAZAO_SOCIAL
     */
    private static function exists($cnpj, $razao_social) {

        $exists = Instituicao::where(function($query) use ($cnpj, $razao_social) {

            $query->where('cnpj', $cnpj)
                ->orWhere('razao_social', $razao_social);
            }
        )->first();

        return !is_null($exists);
    }
}
