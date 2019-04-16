# (WIP) bol.com Retailer API v3 for PHP

A php implementation for the bol.com retailer api v3.

More information:

[https://developers.bol.com/newretailerapiv3/](https://developers.bol.com/newretailerapiv3/)

## Getting Started

```php
use BolRetailerAPI\Api;

$client_id = '{client_id}'
$client_secret = '{client_secret}'

$client = new Api($client_id, $client_secret);

// Set demo mode if wanted
// $client->demoMode();
```

## Methods

### Comissions
```php
// Get commision data for a single EAN
$client->getCommission('8712626055143', 30.00, 'NEW')

// Get commision data for a many EAN's
$client->getCommissions(array(
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
));
```

### Inventory
```php
// Get inventory for current user
$client->getInventory();
```


## Running Tests

The tests require a working bol.com api key.