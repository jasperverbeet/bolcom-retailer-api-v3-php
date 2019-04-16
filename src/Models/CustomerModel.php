<?php

namespace BolRetailerAPI\Models;

class CustomerModel extends BaseModel
{
    //{
//   "rmaId" : 86127131,
//   "orderId" : "7616247328",
//   "ean" : "8718526069334",
//   "title" : "Star Wars - Nappy Star wars T-shirt - XL",
//   "quantity" : 1,
//   "registrationDateTime" : "2018-04-27T19:55:12+02:00",
//   "returnReason" : "Verkeerd besteld",
//   "returnReasonComments" : "Ik wilde eigenlijk een groter formaat",
//   "customerDetails" : {
//     "salutationCode" : "01",
//     "firstName" : "Chewbakka",
//     "surname" : "Wookiee",
//     "streetName" : "Kashyyykstraat",
//     "houseNumber" : "100",
//     "zipCode" : "3528BJ",
//     "city" : "Utrecht",
//     "countryCode" : "NL",
//     "email" : "25whxgzlkmrvs47wsq2yohuwgfnwzk&verkopen.test2.bol.com"
//   },
//   "fulfilmentMethod" : "FBB",
//   "handled" : false,
//   "trackAndTrace" : "3SXOLD7654321"
// }

    /**
     * @var string
     */
    public $ean;

    /**
     * @var string
     */
    public $bsku;

    /**
     * @var string
     */
    public $title;

    /**
     * @var integer
     */
    public $nckStock;

    /**
     * @var integer
     */
    public $stock;

    /**
     * Validate this model, assert that all required values are set.
     */
    public function validate(): void
    {
        $this->assertType($this->ean, 'string');
        $this->assertType($this->bsku, 'string');
        $this->assertType($this->title, 'string');
        $this->assertType($this->nckStock, 'integer');
        $this->assertType($this->stock, 'integer');
    }
}