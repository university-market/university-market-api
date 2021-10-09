<?php

namespace App\Common\Datatype;

class KeyValuePair {

    public $key;
    public $value;

    function __construct($key = null, $value = null) {

        $this->key = $key;
        $this->value = $value;
    }
}