<?php

namespace App\Base\Models;

// Base
use App\Base\Exceptions\UniversityMarketException;

// Common
use \Illuminate\Database\Eloquent\Model;

/**
 * @class `UniversityMarketModel`
 * @description Database Model padrão do projeto University Market
 */
class UniversityMarketModel extends Model {

    /**
     * Properties for entities timestamp control
     */
    /**
     * Created at default Eloquent database column
     * @property Datetime $created_at
     */
    private $created_at;
    /**
     * Updated at default Eloquent database column
     * @property Datetime $updated_at
     */
    private $updated_at;
    /**
     * Deleted at default University Market database column - optional column - can be replaced by a flag (`deleted`)
     * @property Datetime $deleted_at
     */
    private $deleted_at;

    private function getCreatedAtValue() {
        
        return $this->created_at ?? null;
    }

    private function getUpdatedAtValue()
    {
        return $this->updated_at ?? null;
    }

    private function getDeletedAtValue()
    {
        return $this->deleted_at ?? null;
    }

    private function setDeletedAtValue($value)
    {
        if (is_null($value))
            throw new UniversityMarketException("Value of deleted_at column on update clause can't be null");

        $this->deleted_at = $value;
    }

}