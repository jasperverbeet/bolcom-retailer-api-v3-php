<?php

namespace BolRetailerAPI\Models;

use BolRetailerAPI\Exceptions\SerializationException;
use BolRetailerAPI\Exceptions\UnexpectedResponseException;
use BolRetailerAPI\Exceptions\ModelValidationException;

abstract class BaseModel
{
    const MODEL_NAMESPACE = 'BolRetailerAPI\\Models\\';

    /**
     * Assert a type
     * 
     * @param mixed $var The variable to test
     * @param string $type A string representation of the type to test
     */
    public function assertType($var, string $type)
    {
        echo gettype($var);

        if (gettype($var) != $type)
        {
            throw new ModelValidationException;
        }
    }

    /**
     * Deserialize a json string.
     * 
     * @param string $input The input json string.
     */
    static function deserialize(string $input): object
    {
        $json = json_decode($input);

        if (!is_object($json)) {
            throw new SerializationException('Failed to deserialize: ' . $input);
        }

        return $json;
    }

    /**
     * Use this method to populate the model
     * 
     * @param string $input The input json string.
     * 
     * @return object Returns an instance of the current class
     */
    static function fromResponse(string $input): object
    {
        $deserialized = self::deserialize($input);
        
        $ref = new \ReflectionClass(static::class);
        $instance = $ref->newInstanceWithoutConstructor();

        foreach ($deserialized as $propertyName => $propertyValue) {
            $propRef = $ref->getProperty($propertyName);
            $propRef->setAccessible(true);
            $propRef->setValue($instance, $propertyValue);
        }

        try {
            $instance->validate();
        } catch (ModelValidationException $th) {
            throw new UnexpectedResponseException('Unexpected response: ' . $input);
        };

        return $instance;
    }

    /**
     * Validate that all required attributes are set on the model.
     */
    abstract function validate(): void;
}
