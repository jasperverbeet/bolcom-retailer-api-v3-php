<?php

namespace Jasperverbeet\BolRetailerAPI;

use Jasperverbeet\BolRetailerAPI\Exceptions\AuthenticationException;

class Authentication extends BaseClient
{
    private $client_id = '';
    private $client_secret = '';
    private $token = '';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Set user credentials. Obtain these credentials at:
     * https://developers.bol.com/apiv3credentials/
     * @param string $client_id Client id
     * @param string $client_id Client secret
     */
    public function setCredentials($client_id = '', $client_secret): void
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    /**
     * Authenticate user credentials with bolcom api.
     */
    public function authenticate(): void
    {
        if ($this->client_id == '' || $this->client_secret == '') {
            throw new \InvalidArgumentException('Client credentials not set, use the setCredentials method.');
        }

        // Authentication request
        $response = $this->post(
            self::TOKEN_ENDPOINT,
            array(
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret
            )
        );

        if ($response->getStatusCode() == 401) {
            throw new AuthenticationException('Client not authorized.');
        }

        
    }
}
