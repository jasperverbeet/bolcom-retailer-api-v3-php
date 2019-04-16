<?php

namespace BolRetailerAPI\Models;

use Carbon\Carbon;

class ShipmentModel extends BaseModel
{
    /**
     * @var int
     */
    public $shipmentId;

    /**
     * @var Carbon\Carbon
     */
    public $shipmentDate;

    /**
     * @var string
     */
    public $shipmentReference;

    /**
     * @var object
     */
    public $shipmentItems;

    /**
     * @var object
     */
    public $transport;

    /**
     * Validate this model, assert that all required values are set.
     */
    public function validate(): void
    {
        $this->assertType($this->shipmentId, 'integer');
        $this->assertType($this->shipmentDate, 'string');
        $this->assertType($this->shipmentReference, 'string');
        $this->shipmentDate = Carbon::parse($this->shipmentDate);
    }
}