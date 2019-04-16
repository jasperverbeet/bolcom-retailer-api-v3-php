<?php

namespace BolRetailerAPI;

use BolRetailerAPI\Client\AuthenticatedClient;
use BolRetailerAPI\Models\CommissionModel;
use GuzzleHttp\Exception\ClientException;
use BolRetailerAPI\Models\OfferModel;
use BolRetailerAPI\Models\EventModel;
use BolRetailerAPI\Models\ReturnModel;
use BolRetailerAPI\Models\ReductionModel;
use BolRetailerAPI\Models\ShipmentModel;

/**
 * Api Class
 *
 * This class can be used to directly acces the api. More information about the
 * available endpoints can be found at github. https://github.com/jasperverbeet/bolcom-retailer-api-v3-php.
 * 
 * Technical documentation is available at https://api.bol.com/retailer/public/apispec/formatted/index.html
 * @author Jasper Verbeet
 */
class Api
{
    private $client;

    /**
     * To construct the api a client_id and client_secret are needed. The credentials
     * can be found here https://developers.bol.com/apiv3credentials/
     * 
     * @param string $client_id The client ID to authenticate with.
     * @param string $client_secret The client Secret to authenticate with.
     */
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
     * 
     * @param int $page The page for the paginated model. A single page consists
     * of 50 unique items.
     */
    public function getInventory(int $page = 1): array
    {
        $resp = $this->client->authRequest("inventory", "GET", array(
            "page" => $page,
        ));

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
     * @param int $page The page for the paginated model. A single page consists
     * of 50 unique items.
     * 
     * @return ReturnModel[]
     */
    public function getReturns(string $fulfilment = "FBB", bool $handled = false, int $page = 1): array
    {
        $resp = $this->client->authRequest("returns", "GET", array(
            "fulfilment-method" => $fulfilment,
            "handled" => $handled,
            "page" => $page,
        ));

        $deserialized = Serializer::deserialize((string)$resp->getBody());

        if (!property_exists($deserialized, 'returns')) return array();
        return ReturnModel::manyFromResponse($deserialized->returns);
    }

    /**
     * Get all return statusses for handled and unhandled orders.
     * https://api.bol.com/retailer/public/demo/returns.html
     * 
     * @param string $fulfilment Fulfilment type. Options are: FBB | FBR
     * @param int $page The page for the paginated model. A single page consists
     * of 50 unique items.
     * 
     * @return ReturnModel[]
     */
    public function getAllReturns(string $fulfilment = "FBB", int $page = 1): array
    {
        return array_merge(
            $this->getReturns($fulfilment, false, $page),
            $this->getReturns($fulfilment, true, $page),
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
     * @param int $page The page for the paginated model. A single page consists
     * of 50 unique items.
     * 
     * @return ReductionModel[]
     */
    public function getReductions(int $page = 1) : array
    {
        $resp = $this->client->authRequest("reductions", "GET", array(
            "page" => $page,
        ), array(
            "Accept" => "application/vnd.retailer.v3+csv",
        ));

        $deserialized = Serializer::deserializeCSV((string) $resp->getBody());
        return ReductionModel::manyFromResponse($deserialized);
    }

    /**
     * A paginated list to retrieve all your shipments. The shipments will be
     * sorted by date in descending order.
     * 
     * @param string $fulfilment Fulfilment type. Options are: FBB | FBR
     * @param int $page The page for the paginated model. A single page consists
     * of 50 unique items.
     */
    public function getShipments(string $fulfilment = "FBB", int $page = 1) : array
    {
        $resp = $this->client->authRequest("shipments", "GET", array(
            "fulfilment-method" => $fulfilment,
            "page" => $page,
        ));

        $deserialized = Serializer::deserialize((string) $resp->getBody());
        return ShipmentModel::manyFromResponse($deserialized->shipments);
    }
}
