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

        var_dump($models);

        $this->assertEquals(count($models), 5);
        $this->assertEquals(count($models[4]->reductions), 1);
    }
}
