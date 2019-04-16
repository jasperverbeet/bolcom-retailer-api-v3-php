<?php
namespace BolRetailerAPI\Tests;

use BolRetailerAPI\Tests\BolTestCase;
use BolRetailerAPI\Api;
use BolRetailerAPI\Exceptions\AuthenticationException;

class ApiTest extends BolTestCase
{
    private $api;

    protected function setUp() : void
    {
        $this->api = new Api(getenv('API_CLIENT_ID'), getenv('API_CLIENT_SECRET'));
        $this->api->demoMode();
    }

    public function testCommission()
    {
        $model = $this->api->getCommission('8712626055143');
        $this->assertEquals($model->ean, '8712626055143');
        $this->assertEquals($model->condition, 'NEW');
        $this->assertEquals($model->price, null);
        $this->assertEquals($model->fixedAmount, 0.99);
        $this->assertEquals($model->percentage, 15);
    }

    public function testCommissionPrice()
    {
        $model = $this->api->getCommission('8712626055143', 10.00, 'GOOD');
        $this->assertEquals($model->ean, '8712626055143');
        $this->assertEquals($model->condition, 'GOOD');
        $this->assertEquals($model->price, 10.00);
        $this->assertEquals($model->fixedAmount, 0.99);
        $this->assertEquals($model->percentage, 15);
        $this->assertEquals($model->totalCost, 4.67);
    }

    public function testCommissions()
    {
        $models = $this->api->getCommissions(array(
            array(
                "ean" => "8712626055150",
                "condition" => "NEW",
                "price" => 34.99,
            ),
            array(
                "ean" => "8804269223123",
                "condition" => "NEW",
                "price" => 699.95,
            ),
            array(
                "ean" => "8712626055143",
                "condition" => "GOOD",
                "price" => 24.50,
            ),
            array(
                "ean" => "0604020064587",
                "condition" => "NEW",
                "price" => 24.95,
            ),
            array(
                "ean" => "8718526069334",
                "condition" => "NEW",
                "price" => 25.00,
            )
        ));

        $this->assertEquals(count($models), 5);
        $this->assertEquals(count($models[4]->reductions), 1);
    }

    public function testInventory()
    {
        $models = $this->api->getInventory();
        $this->assertEquals(count($models), 1);
        $this->assertEquals($models[0]->ean, "8718526069334");
    }

    public function testUpdateReturnStatus()
    {
        $model = $this->api->updateReturn('86123452', "RETURN_RECEIVED", 3);
        $this->assertEquals($model->status, "PENDING");
    }

    public function testGetReturn()
    {
        $model = $this->api->getReturn("86123452");
        $this->assertEquals($model->title, "Star Wars - Original Trilogy");
    }

    public function testGetUnhandledReturns()
    {
        $models = $this->api->getReturns('FBB');
        $this->assertEquals(count($models), 1);
        $models = $this->api->getAllReturns('FBR');
        $this->assertEquals(count($models), 8);
    }

    public function testGetFullName()
    {
        $model = $this->api->getReturn("86127131");
        $this->assertEquals($model->customerDetails->getFullName(), "Chewbakka Wookiee");
    }

    public function testUpdateTransport()
    {
        $model = $this->api->updateTransport('358612589', 'TNT', '3SAOLD1234567');
        $this->assertEquals($model->status, "PENDING");
    }
}
