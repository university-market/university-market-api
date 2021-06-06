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
     * Convert an object to a specific class.
     * @param object $object
     * @param string $class_name The class to cast the object to
     * @return object
     */
    protected function makeModel($object, $class_name) {

        if ($object === false || \is_null($object)) return null;

        $finalClass = \is_object($class_name) ? $class_name : 
            class_exists($class_name) ? new $class_name() : null;
        
        if (\is_null($finalClass))
            throw new \Exception("Não foi possível encontrar a classe $class_name para realizar o type casting");

        foreach ((array)$finalClass as $property => $value)
            if (\property_exists($finalClass, $property))
                $finalClass->$property = $object->$property;

        return $finalClass;
    }
}