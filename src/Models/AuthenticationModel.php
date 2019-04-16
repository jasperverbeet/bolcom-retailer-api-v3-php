<?php

namespace BolRetailerAPI\Models;

class AuthenticationModel extends BaseModel
{
    /**
     * @var int Expires in (seconds)
     */
    public $expires_in;

    /**
     * @var string Access token
     */
    public $access_token;

    /**
     * @var string Scope
     */
    public $scope;

    /**
     * @var string Token type
     */
    public $token_type;

    /**
     * Validate this model, assert that all required values are set.
     */
    public function validate(): void
    {
        $this->assertType($this->access_token, 'string');
        $this->assertType($this->expires_in, 'integer');
        $this->assertType($this->scope, 'string');
        $this->assertType($this->token_type, 'string');
    }
}