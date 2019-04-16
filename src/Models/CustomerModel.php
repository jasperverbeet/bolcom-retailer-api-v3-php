<?php

namespace BolRetailerAPI\Models;

class CustomerModel extends BaseModel
{
    /**
     * @var string
     */
    public $salutationCode;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $surname;

    /**
     * @var string
     */
    public $streetName;

    /**
     * @var string
     */
    public $houseNumberExtended;

    /**
     * @var string
     */
    public $deliveryPhoneNumber;

    /**
     * @var string
     */
    public $houseNumber;

    /**
     * @var string
     */
    public $zipCode;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $countryCode;

    /**
     * @var string
     */
    public $email;

    /**
     * Get full name of this contact
     */
    public function getFullName() : string
    {
        return "{$this->firstName} {$this->surname}";
    }

    /**
     * Validate this model, assert that all required values are set.
     */
    public function validate(): void
    {
        $this->assertType($this->salutationCode, 'string');
        $this->assertType($this->firstName, 'string');
        $this->assertType($this->surname, 'string');
        $this->assertType($this->streetName, 'string');
        $this->assertType($this->houseNumber, 'string');
        $this->assertType($this->zipCode, 'string');
        $this->assertType($this->city, 'string');
        $this->assertType($this->countryCode, 'string');
        $this->assertType($this->email, 'string');
    }
}