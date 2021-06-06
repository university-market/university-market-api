<?php

namespace App\Http\Controllers\Base;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UniversityMarketController extends BaseController {

    /**
     * @property array $contentType Content type to non-null methods
     */
    protected $contentType = ['Content-type' => 'application/json'];

    /**
     * Convert an or more objects to a specific class.
     * @method cast()
     * @param object|object[] $object Initial object
     * @param string $class_name The class to cast the object to
     * @return object|object[]
     */
    protected function cast($object, $class_name) {

        if ($object === false || \is_null($object)) return null;

        // Test class exists
        if (\is_null($this->makeModel($class_name)))
            throw new \Exception("Não foi possível encontrar a classe $class_name para realizar o type casting");

        if (!is_array($object)) {

            $finalClass = $this->makeModel($class_name);

            foreach ((array)$finalClass as $property => $value)
                if (\property_exists($finalClass, $property))
                    $finalClass->$property = \is_array($object) ? $object[$property] : $object->$property;

            return $finalClass;
        }

        $finalCollection = [];

        foreach ($object as $obj) {

            $finalClass = $this->makeModel($class_name);

            foreach ((array)$finalClass as $property => $value)
                if (\property_exists($finalClass, $property))
                    $finalClass->$property = \is_array($obj) ? $obj[$property] : $obj->$property;

            $finalCollection[] = $finalClass;
        }
        return $finalCollection;
    }

    private function makeModel($class_ref) {

        return \is_object($class_ref) ? $class_ref : 
            (class_exists($class_ref) ? new $class_ref() : null);
    }
}