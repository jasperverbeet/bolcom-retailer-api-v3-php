<?php

namespace BolRetailerAPI;

use BolRetailerAPI\Client\AuthenticatedClient;

class Api
{
    private $client;

    function __construct(string $client_id, string $client_secret)
    {
        if (is_null($client_id) || is_null($client_secret))
        {
            throw new \InvalidArgumentException('Supply a client_id and client_secret!');
        }

        $this->client = new AuthenticatedClient;
        $this->client->setCredentials($client_id, $client_secret);
        $this->client->authenticate();
    }

    
}