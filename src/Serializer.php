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

    /**
     * Will turn CSV naming conventions to PhP convention.
     * 
     * examples:
     * - HelloWorld => helloWorld
     * - EAN => ean
     * 
     * @param string $input The input string.
     */
    static function toPhpVariableConvention(string $input) {
        if (strtoupper($input) == $input) return strtolower($input);
        return lcfirst($input);
    }

    /**
     * Tries to convert the input to a float or integer.
     * 
     * @param string $input The input string.
     */
    static function toNativeType(string $input)
    {
        if (is_numeric($input) && strpos($input, ".") !== false) return (double) $input;
        if (is_numeric($input)) return (integer) $input;
        return $input;
    }

    /**
     * Deserialize csv data to array
     * 
     * @param string $input The input CSV as string. New lines are seperated with
     * \n, columns delimetered with ".
     */
    public static function deserializeCSV(string $input): array
    {
        // Load CSV
        $csv = str_getcsv($input, "\n");
        
        // Convert header row to php conventions
        $headerRow = array_map("self::toPhpVariableConvention", str_getcsv($csv[0]));

        // Combine each data row with header row to generate key => value pairs
        array_walk($csv, function(&$a) use ($headerRow) {
            $a = array_combine($headerRow, str_getcsv($a));
            $a = array_map("self::toNativeType", $a);
        });
        
        // Remove header row
        array_shift($csv);
        
        return $csv;
    }
}