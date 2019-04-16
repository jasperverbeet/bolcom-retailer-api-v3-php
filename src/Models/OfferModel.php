<?php

namespace BolRetailerAPI\Models;

class OfferModel extends BaseModel
{
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