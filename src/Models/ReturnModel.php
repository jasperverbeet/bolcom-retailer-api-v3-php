<?php

namespace BolRetailerAPI\Models;

class ReturnModel extends BaseModel
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

// "handlingResult" : "RETURN_RECEIVED",
//   "processingResult" : "ACCEPTED",
//   "processingDateTime" : "2018-04-29T16:36:54+02:00"

    /**
     * @var int
     */
    public $rmaId;

    /**
     * @var string
     */
    public $orderId;

    /**
     * @var string
     */
    public $ean;

    /**
     * @var string
     */
    public $title;

    /**
     * @var integer
     */
    public $quantity;

    /**
     * @var Carbon\Carbon
     */
    public $registrationDateTime;

    /**
     * @var string
     */
    public $returnReason;

    /**
     * @var string
     */
    public $returnReasonComments;

    /**
     * @var string
     */
    public $fulfilmentMethod;

    /**
     * @var boolean
     */
    public $handled;

    /**
     * @var string
     * 
     */
    public $trackAndTrace;

    /**
     * @var string
     */
    public $handlingResult;

    /**
     * @var string
     */
    public $processingResult;

    /**
     * @var Carbon\Carbon
     */
    public $processingDateTime;

    /**
     * @var object
     * TODO: Move to custom model
     */
    public $customerDetails;

    /**
     * Validate this model, assert that all required values are set.
     */
    public function validate(): void
    {
        $this->assertType($this->rmaId, 'integer');
        $this->assertType($this->orderId, 'string');
        $this->assertType($this->ean, 'string');
        $this->assertType($this->quantity, 'integer');
        $this->assertType($this->registrationDateTime, 'string');
        // $this->assertType($this->returnReason, 'string');
        // $this->assertType($this->returnReasonComments, 'string');
        $this->assertType($this->handled, 'boolean');

        if (!is_null($this->customerDetails))
        {
            $this->customerDetails = CustomerModel::fromResponse($this->customerDetails);
        }
    }
}