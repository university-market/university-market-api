<?php

namespace App\Base\Logs\Type;

// Base
use App\Base\Logs\Interfaces\LogChangeSerializer;
use App\Base\Exceptions\UniversityMarketException;

// Common
use stdClass;

class StdLogChange implements LogChangeSerializer {

    private $_before;
    private $_after;

    public function __construct()
    {
        return $this;
    }

    /**
     * Persiste o estado de um objeto antes de sua alteração
     * 
     * @method setBeforeState
     */
    public function setBeforeState($model) {

        if (is_null($model))
            throw new UniversityMarketException("Cannot set property 'before' in log change with null");

        $this->_before = $model;

        return $this;
    }

    /**
     * Persiste o estado de um objeto depois de sua alteração
     * 
     * @method setAfterState
     */
    public function setAfterState($model) {

        if (is_null($model))
            throw new UniversityMarketException("Cannot set property 'after' in log change with null");

        $this->_after = $model;

        return $this;
    }

    /**
     * Serializar mudanças persistidas para serem armazenadas no log
     * 
     * @method serializeChanges
     * 
     * @return string Model de mudanças serializadas como JSON
     */
    public function serializeChanges() {

        $change_entity = new stdClass();

        $change_entity->before = $this->_before;
        $change_entity->after = $this->_after;

        $change_serialized = json_encode($change_entity);

        return $change_serialized;
    }
}