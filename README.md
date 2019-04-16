# (WIP) bol.com Retailer API v3 for PHP

A php implementation for the bol.com retailer api v3.

More information:

[https://developers.bol.com/newretailerapiv3/](https://developers.bol.com/newretailerapiv3/)

## Getting Started

```php
use BolRetailerAPI\Api;

$client_id = "{client_id}"
$client_secret = "{client_secret}"

$client = new Api($client_id, $client_secret);

// Set demo mode if wanted
$client->demoMode();
```

## Methods

### Comissions
[https://api.bol.com/retailer/public/demo/commission.html](https://api.bol.com/retailer/public/demo/commission.html)
```php
// Get commision data for a single EAN
$client->getCommission("8712626055143", 30.00, "NEW")

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
[https://api.bol.com/retailer/public/demo/inventory.html](https://api.bol.com/retailer/public/demo/inventory.html)
```php
// Get inventory for current user
$client->getInventory();
```

### Returns
[https://api.bol.com/retailer/public/demo/returns.html](https://api.bol.com/retailer/public/demo/returns.html)
```php
// Update a specific return status
$client->updateReturn("86123452", "RETURN_RECEIVED", 3);

// Get the return status for a specific order
$client->getReturn("86123452");

// Get the return statuses for many handled orders
$client->getReturns("FBB", true);

// Get the return statuses for many unhandled orders
$client->getReturns("FBB", false);

// Get all return statuses for orders
$client->getAllReturns("FBR");
```

### Transports
[https://api.bol.com/retailer/public/demo/transports.html](https://api.bol.com/retailer/public/demo/transports.html)
```php
// Add a specific transport
$client->updateTransport("358612589", "TNT", "3SAOLD1234567");
```

### Reductions
[https://api.bol.com/retailer/public/demo/reductions.html](https://api.bol.com/retailer/public/demo/reductions.html)
```php
// Get all reductions for the current user
$client->getReductions();
```

## TODO

- ~Commission~
- ~Returns~
- ~Inventory~
- ~Transports~
- ~Reductions~
- Inbounds
- Invoices
- Offers
- Orders
- Process status
- Shipments
- Shipping labels


## Running Tests

The tests require a working bol.com api key.