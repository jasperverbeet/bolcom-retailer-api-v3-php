<?php
namespace BolRetailerAPI\Tests;

use BolRetailerAPI\Tests\BolTestCase;
use BolRetailerAPI\Exceptions\SerializationException;
use BolRetailerAPI\Serializer;

class SerializerTest extends BolTestCase
{

    public function testSerializeInvalid()
    {
        $this->expectException(SerializationException::class);
        Serializer::deserialize("{invalid: object}");
    }

    public function testSerializeValid()
    {
        $deserialized = Serializer::deserialize("{\"valid\": \"object\"}");
        $this->assertAttributeEquals("object", "valid", $deserialized);
    }
}
