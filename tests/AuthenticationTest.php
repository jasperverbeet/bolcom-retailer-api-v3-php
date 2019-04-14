<?php
namespace Jasperverbeet\Tests;

use Jasperverbeet\Tests\BolTestCase;
use Jasperverbeet\BolRetailerAPI\Authentication;
use Jasperverbeet\BolRetailerAPI\Exceptions\AuthenticationException;

class AuthenticationTest extends BolTestCase
{
    private $client;

    protected function setUp()
    {
        $this->client = new Authentication;
        $this->client_id = getenv('API_CLIENT_ID');
        $this->client_secret = getenv('API_CLIENT_SECRET');
    }

    public function testCredentialsNotSet()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->client->authenticate();
    }

    public function testInvalidCredentials()
    {
        $this->expectException(AuthenticationException::class);
        
        $this->client->setCredentials('a', 'b');
        $this->client->authenticate();
    }

    public function testValidCredentials()
    {
        $this->client->setCredentials($this->client_id, $this->client_secret);
        $this->client->authenticate();
    }
}
