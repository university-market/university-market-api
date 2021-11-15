<?php

namespace App\Models\Instituicao;

// Base
use App\Base\Models\UniversityMarketModel;
use App\Base\Exceptions\UniversityMarketException;

// Models
use App\Models\Plano\Plano;

class Instituicao extends UniversityMarketModel {
    
    // Nome da entidade no banco de dados
    protected $table = 'Instituicoes';

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

        // Remover eventual mascara
        $cnpj = preg_replace("/[^0-9]/", '', $value);

        if (strlen($cnpj) != 14)
            throw new UniversityMarketException("CNPJ informado não é válido");

        if (self::exists($cnpj, null))
            throw new UniversityMarketException("O CNPJ informado já se encontra cadastrado");

        $this->attributes['cnpj'] = $cnpj;
    }

    // Setter para Razao_Social
    public function setRazaoSocialAttribute($value) {

        // Formato padrão ao salvar razao social de uma instituicao (upper case)
        $razao_social = strtoupper(trim($value));

        if (self::exists(null, $razao_social))
            throw new UniversityMarketException("A Razão Social informada já se encontra cadastrada");

        $this->attributes['razao_social'] = $razao_social;
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

    /**
     * Listar instituicoes disponíveis para que estudantes se cadastrem
     * 
     * @return array Aprovadas e ativas
     */
    public static function getDisponiveis() {

        $disponiveis = Instituicao::where('ativa', true)
            ->where('approved_at', '!=', null)
            ->get();

        return $disponiveis;
    }
}
