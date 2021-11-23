<?php

namespace App\Models\Base;

// Base
use App\Base\Models\UniversityMarketModel;
use App\Base\Exceptions\UniversityMarketException;

// Common
use Illuminate\Support\Facades\Hash;
use App\Common\Constants\UniversityMarketConstants;

// Models

class UniversityMarketActorBase extends UniversityMarketModel
{

    // Registrar data/hora criacao/alteracao
    // public $timestamps = true;

    // Primary Key da entidade Base
    protected $id;
    protected $primaryKey = 'id';

    // Type Casting para propriedades com valores especiais (não-string)
    protected $casts = [
        'ativo' => 'boolean'
    ];

    // Base Columns
    protected $nome;
    protected $email;
    protected $senha;
    protected $ativo;

    // Timestamps
    // private $created_at;

    // Setter para Nome
    public function setNomeAttribute($value)
    {

        $counter = explode(' ', $value ?? '');

        // Ao menos um nome informado
        if (count($counter) <= 1)
            throw new UniversityMarketException("Um nome completo deve ser fornecido");

        $this->attributes['nome'] = $value;
    }

    // Setter para Senha
    public function setSenhaAttribute($value)
    {

        $password_config = UniversityMarketConstants::password();

        if (is_null($value))
            throw new UniversityMarketException("A senha é obrigatória");

        if (strlen($value) < $password_config['min_length'])
            throw new UniversityMarketException("A senha deve conter ao menos {$password_config['min_length']} caracteres");

        $this->attributes['senha'] = Hash::make($value);
    }
}
