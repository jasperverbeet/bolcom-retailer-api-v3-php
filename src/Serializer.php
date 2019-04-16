<?php

namespace BolRetailerAPI;

use BolRetailerAPI\Exceptions\SerializationException;

class Serializer
{
    /**
     * Deserialize a json string.
     * 
     * @param string $input The input json string.
     */
    public static function deserialize(string $input): object
    {
        $json = json_decode($input);

        if (!is_object($json)) {
            throw new SerializationException('Failed to deserialize: ' . $input);
        }

        return $json;
    }
}