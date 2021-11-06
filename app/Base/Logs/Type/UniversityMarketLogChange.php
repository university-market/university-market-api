<?php

namespace App\Base\Logs\Type;

// Base
use App\Base\Logs\Interfaces\LogChangeSerializer;
use App\Base\Exceptions\UniversityMarketException;

// Common
use stdClass;

class UniversityMarketLogChange implements LogChangeSerializer {

    private $_before;
    private $_after;

    public function __construct()
    {
        return $this;
    }

    public function setBefore($model) {

        if (is_null($model))
            throw new UniversityMarketException("Cannot set property 'before' in log change with null");

        $this->_before = $model;

        return $this;
    }

    public function setAfter($model) {

        if (is_null($model))
            throw new UniversityMarketException("Cannot set property 'after' in log change with null");

        $this->_after = $model;

        return $this;
    }

    public function serialize() {

        $change_entity = new stdClass();

        $change_entity->before = $this->_before;
        $change_entity->after = $this->_after;

        $change_serialized = json_encode($change_entity);

        return $change_serialized;
    }
}