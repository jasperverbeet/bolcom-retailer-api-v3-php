<?php

namespace BolRetailerAPI\Models;

class OfferModel extends BaseModel
{
    // ["ean"]=>
    // string(13) "8718526069334"
    // ["bsku"]=>
    // string(13) "2950003119187"
    // ["nckStock"]=>
    // int(0)
    // ["stock"]=>
    // int(5)
    // ["title"]=>
    // string(40) "Star Wars - Nappy Star wars T-shirt - XL"


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