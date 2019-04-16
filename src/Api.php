<?php

namespace BolRetailerAPI;

use BolRetailerAPI\Client\AuthenticatedClient;
use BolRetailerAPI\Models\CommissionModel;
use GuzzleHttp\Exception\ClientException;
use BolRetailerAPI\Models\OfferModel;
use BolRetailerAPI\Models\EventModel;
use BolRetailerAPI\Models\ReturnModel;
use BolRetailerAPI\Models\ReductionModel;

class Api
{
    private $client;

    function __construct(string $client_id, string $client_secret)
    {
        if (is_null($client_id) || is_null($client_secret)) {
            throw new \InvalidArgumentException("Supply a client_id and client_secret!");
        }

        $this->client = new AuthenticatedClient;
        $this->client->setCredentials($client_id, $client_secret);
        $this->client->authenticate();
    }

    /**
     * Use demo environment instead of production to test out api calls.
     */
    public function demoMode(): void
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
    public function getCommission(string $ean, float $price = 0.00, string $condition = "NEW"): CommissionModel
    {
        $resp = $this->client->authRequest("commission/${ean}", "GET", array(
            "price" => $price,
            "condition" => $condition,
        ));

        $deserialized = Serializer::deserialize((string)$resp->getBody());
        return CommissionModel::fromResponse($deserialized);
    }

    /**
     * Retrieve commission information on multiple EAN"s
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
    public function getCommissions(array $products): array
    {
        $resp = $this->client->authRequest("commission/", "POST", array(
            "commissionQueries" => $products,
        ));

        $deserialized = Serializer::deserialize((string)$resp->getBody());
        return CommissionModel::manyFromResponse($deserialized->commissions);
    }

    /**
     * Retrieve inventory of the current user.
     */
    public function getInventory(): array
    {
        $resp = $this->client->authRequest("inventory", "GET");

        $deserialized = Serializer::deserialize((string)$resp->getBody());
        return OfferModel::manyFromResponse($deserialized->offers);
    }

    /**
     * Update return status for an order.
     * https://api.bol.com/retailer/public/demo/returns.html
     * 
     * @param string $rmaId The bol.com internal retour id.
     * @param string $handlingResult New handling status. Options are:
     * EXCHANGE_PRODUCT | RETURN_RECEIVED
     * @param int $quantityReturned The amount of received goods.
     */
    public function updateReturn(string $rmaId, string $handlingResult = "RETURN_RECEIVED", int $quantityReturned = 1): EventModel
    {
        $resp = $this->client->authRequest("returns/{$rmaId}", "PUT", array(
            "handlingResult" => $handlingResult,
            "quantityReturned" => $quantityReturned,
        ));

        $deserialized = Serializer::deserialize((string)$resp->getBody());
        return EventModel::fromResponse($deserialized);
    }

    /**
     * Get return status for an order
     * https://api.bol.com/retailer/public/demo/returns.html
     * 
     * @param string $rmaId The bol.com internal retour id.
     */
    public function getReturn(string $rmaId): ReturnModel
    {
        $resp = $this->client->authRequest("returns/${rmaId}", "GET");

        $deserialized = Serializer::deserialize((string)$resp->getBody());
        return ReturnModel::fromResponse($deserialized);
    }

    /**
     * Get return statusses for many orders.
     * https://api.bol.com/retailer/public/demo/returns.html
     * 
     * @param string $fulfilment Fulfilment type. Options are: FBB | FBR
     * @param boolean $handled Whether the handled returns should be returns or
     * the unhandled.
     * 
     * @return ReturnModel[]
     */
    public function getReturns(string $fulfilment = "FBB", bool $handled = false): array
    {
        $resp = $this->client->authRequest("returns", "GET", array(
            "fulfilment-method" => $fulfilment,
            "handled" => $handled,
        ));

        $deserialized = Serializer::deserialize((string)$resp->getBody());
        return ReturnModel::manyFromResponse($deserialized->returns);
    }

    /**
     * Get all return statusses for handled and unhandled orders.
     * https://api.bol.com/retailer/public/demo/returns.html
     * 
     * @param string $fulfilment Fulfilment type. Options are: FBB | FBR
     * 
     * @return ReturnModel[]
     */
    public function getAllReturns(string $fulfilment = "FBB"): array
    {
        return array_merge(
            $this->getReturns($fulfilment, false),
            $this->getReturns($fulfilment, true),
        );
    }

    /**
     * Update a transport status to shipped.
     * https://api.bol.com/retailer/public/demo/transports.html
     * 
     * @param string $orderId The order id.
     * @param string $transporterCode The transporter (i.e. TNT).
     * @param string $trackAndTrace The track and trace code.
     * 
     * @return EventModel
     */
    public function updateTransport(string $orderId, string $transporterCode, string $trackAndTrace): EventModel
    {
        $resp = $this->client->authRequest("transports/{$orderId}", "PUT", array(
            "transporterCode" => $transporterCode,
            "trackAndTrace" => $trackAndTrace,
        ));

        $deserialized = Serializer::deserialize((string)$resp->getBody());
        return EventModel::fromResponse($deserialized);
    }

    /**
     * This endpoint will return a list EAN’s that are eligible for reductions on the commission fee.
     * https://api.bol.com/retailer/public/demo/reductions.html
     * 
     * @return ReductionModel[]
     */
    public function getReductions() : array
    {
        $resp = $this->client->authRequest("reductions", "GET", array(), array(
            "Accept" => "application/vnd.retailer.v3+csv",
        ));

        $deserialized = Serializer::deserializeCSV((string) $resp->getBody());
        return ReductionModel::manyFromResponse($deserialized);
    }
}
