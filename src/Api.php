<?php

namespace BolRetailerAPI;

use BolRetailerAPI\Client\AuthenticatedClient;
use BolRetailerAPI\Models\CommissionModel;
use GuzzleHttp\Exception\ClientException;
use BolRetailerAPI\Models\OfferModel;

class Api
{
    private $client;

    function __construct(string $client_id, string $client_secret)
    {
        if (is_null($client_id) || is_null($client_secret)) {
            throw new \InvalidArgumentException('Supply a client_id and client_secret!');
        }

        $this->client = new AuthenticatedClient;
        $this->client->setCredentials($client_id, $client_secret);
        $this->client->authenticate();
    }

    /**
     * Use demo environment instead of production to test out api calls.
     */
    public function demoMode() : void
    {
        $this->client->setDemoMode();
    }

    /**
     * Retrieve commission information for a single EAN
     * 
     * @param string $ean EAN number to look for
     * @param float $price Price used for calculation
     * @param string $condition Condition of offer (NEW or GOOD)
     */
    public function getCommission(string $ean, float $price = 0.00, string $condition = 'NEW') : CommissionModel
    {
        $resp = $this->client->authRequest("commission/${ean}", 'GET', array(
            'price' => $price,
            'condition' => $condition,
        ));

        $deserialized = Serializer::deserialize((string)$resp->getBody());
        return CommissionModel::fromResponse($deserialized);
    }

    /**
     * Retrieve commission information on multiple EAN's
     * 
     * @param object[] $products a list of products to get commission info on. Example:
     * [{
     *  "ean": "8712626055150",
     *  "condition": "NEW",
     *  "price": 34.99
     * }]
     * 
     * @return CommissionModel[] A list of CommissionModels
     */
    public function getCommissions(array $products) : array
    {
        $resp = $this->client->authRequest("commission/", "POST", array(
            'commissionQueries' => $products,
        ));

        $deserialized = Serializer::deserialize((string)$resp->getBody());
        return CommissionModel::manyFromResponse($deserialized->commissions);
    }

    /**
     * Retrieve inventory of the current user.
     */
    public function getInventory() : array
    {
        $resp = $this->client->authRequest("inventory", "GET");

        $deserialized = Serializer::deserialize((string)$resp->getBody());
        return OfferModel::manyFromResponse($deserialized->offers);
    }

    
}
