<?php
namespace BolRetailerAPI\Tests;

use BolRetailerAPI\Tests\BolTestCase;
use BolRetailerAPI\Client\AuthenticatedClient;
use BolRetailerAPI\Exceptions\AuthenticationException;

class AuthenticationTest extends BolTestCase
{
    private $client;

    protected function setUp() : void
    {
        $this->client = new AuthenticatedClient;
        $this->client_id = getenv('API_CLIENT_ID');
        $this->client_secret = getenv('API_CLIENT_SECRET');
    }

    public function testIsAuthenticatedNoCredentialsSet()
    {
        $this->assertFalse($this->client->isAuthenticated());
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
        $this->assertTrue($this->client->isAuthenticated());
    }
}
