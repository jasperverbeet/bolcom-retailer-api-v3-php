<?php

namespace BolRetailerAPI\Models;

use BolRetailerAPI\Exceptions\ModelValidationException;

abstract class BaseModel
{
    /**
     * Assert a type
     * 
     * @param mixed $var The variable to test
     * @param string $type A string representation of the type to test
     */
    public function assertType($var, string $type)
    {
        if (gettype($var) != $type)
        {
            throw new ModelValidationException;
        }
    }

    /**
     * Create a new instance of this class from the deserialized data.
     * 
     * @param object $deserialized Deserialized json
     */
    static private function createObject($deserialized) : object
    {
        $ref = new \ReflectionClass(static::class);
        $instance = $ref->newInstanceWithoutConstructor();

        foreach ($deserialized as $propertyName => $propertyValue) {
            $propRef = $ref->getProperty($propertyName);
            $propRef->setAccessible(true);
            $propRef->setValue($instance, $propertyValue);
        }

        return $instance;
    }

    /**
     * Use this method to populate the model
     * 
     * @param object $deserialized The deserialized json.
     * 
     * @return object Returns an instance of the current class
     */
    static function fromResponse(object $deserialized): object
    {
        $instance = self::createObject($deserialized);
        $instance->validate();
        return $instance;
    }

    /**
     * Use this method to populate the model
     * 
     * @param array $deserialized The deserialized json.
     * 
     * @return object[] Returns a list of instances of the current class
     */
    static function manyFromResponse(array $deserialized) : array
    {
        $itemList = array();

        foreach($deserialized as $item)
        {
            $instance = self::createObject($item);
            $instance->validate();
            array_push($itemList, $instance);
        }
        return $itemList;
    }

    /**
     * Validate that all required attributes are set on the model.
     */
    abstract function validate(): void;
}
