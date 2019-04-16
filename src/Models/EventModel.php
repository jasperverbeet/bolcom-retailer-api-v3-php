<?php

namespace BolRetailerAPI\Models;

use Carbon\Carbon;

class EventModel extends BaseModel
{
    // {
    //     "id" : 1,
    //     "entityId" : "86123452",
    //     "eventType" : "HANDLE_RETURN_ITEM",
    //     "description" : "Handle the return item with return number 86123452",
    //     "status" : "PENDING",
    //     "createTimestamp" : "2019-04-16T11:55:29.34+02:00",
    //     "links" : [ {
    //       "rel" : "self",
    //       "href" : "http://localhost:44960/retailer-demo/process-status/1",
    //       "method" : "GET"
    //     } ]
    //   }

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $entityId;

    /**
     * @var string
     */
    public $eventType;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $status;

    /**
     * @var Carbon\Carbon
     */
    public $createTimestamp;

    /**
     * @var LinkModel[]
     */
    public $links;

    /**
     * Validate this model, assert that all required values are set.
     */
    public function validate(): void
    {
        $this->assertType($this->id, 'integer');
        $this->assertType($this->entityId, 'string');
        $this->assertType($this->eventType, 'string');
        $this->assertType($this->description, 'string');
        $this->assertType($this->status, 'string');
        $this->assertType($this->createTimestamp, 'string');
        $this->assertType($this->links, 'array');

        $this->createTimestamp = Carbon::parse($this->createTimestamp);
    }
}