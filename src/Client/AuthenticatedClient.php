<?php

namespace BolRetailerAPI\Client;

use BolRetailerAPI\Exceptions\AuthenticationException;
use Carbon\Carbon;
use BolRetailerAPI\Formatters\AuthenticationFormatter;
use BolRetailerAPI\Models\AuthenticationModel;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ClientException;
use BolRetailerAPI\Serializer;

/**
 *  Authenticated Client
 *
 *  A base client with authentication.
 *
 *  @author Jasper Verbeet
 */
class AuthenticatedClient extends BaseClient
{
    private $client_id = '';
    private $client_secret = '';

    private $expiration;
    private $access_token = '';
    private $token_type = 'Bearer';
    private $scope = 'RETAILER';

    private $token_base = 'https://login.bol.com/token';
    private $api_base = 'https://api.bol.com/retailer/';

    /**
     * Set user credentials. Obtain these credentials at:
     * https://developers.bol.com/apiv3credentials/
     * @param string $client_id Client id
     * @param string $client_id Client secret
     */
    public function setCredentials($client_id = '', $client_secret = ''): void
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    public function setDemoMode()
    {
        $this->api_base = 'https://api.bol.com/retailer-demo/';
    }

    /**
     * Returns whether the user has obtained a token and its still valid.
     */
    public function isAuthenticated(): bool
    {
        return $this->access_token != '' && Carbon::now()->isBefore($this->expiration);
    }

    /**
     * Authenticate user credentials with bolcom api.
     */
    public function authenticate(): void
    {
        if ($this->client_id == '' || $this->client_secret == '') {
            throw new \InvalidArgumentException('Client credentials not set, use the setCredentials method.');
        }

        try {
            $response = $this->client->request('POST', $this->token_base, [
                'form_params' => array(
                    'client_id' => $this->client_id,
                    'client_secret' => $this->client_secret,
                    'grant_type' => 'client_credentials',
                )
            ]);
        } catch (ClientException $th) {
            throw new AuthenticationException('Client not authorized.');
        }

        $deserialized = Serializer::deserialize((string)$response->getBody());
        $model = AuthenticationModel::fromResponse($deserialized);

        // These attributes are now safe to access
        $this->access_token = $model->access_token;
        $this->scope = $model->scope;
        $this->token_type = $model->token_type;
        $this->expiration = Carbon::now()->addSeconds($model->expires_in);
    }

    /**
     * Perform an authenticated request
     * 
     * @param string $endpoint Endpoint to do the request
     * @param string $method Either GET or POST
     * @param array $parameters Optional parameters
     * @param array $headers Optional headers
     */
    public function authRequest(string $endpoint, string $method, array $parameters = array(), array $headers = array()): Response
    {
        // (Re) authenticate user if not authenticated
        if ($this->isAuthenticated()) $this->authenticate();

        $parameters = array_filter($parameters);

        // Add authorization header
        $headers['Authorization'] = "{$this->token_type} {$this->access_token}";

        switch ($method) {
            case 'GET':
                return $this->get($this->api_base . $endpoint, $parameters, $headers);
                break;
            case 'POST':
                return $this->post(
                    $this->api_base . $endpoint,
                    $parameters,
                    array_merge(
                        array('Content-Type' => 'application/vnd.retailer.v3+json'),
                        $headers
                    )
                );
                break;
            case 'PUT':
                return $this->put(
                    $this->api_base . $endpoint,
                    $parameters,
                    array_merge(
                        array('Content-Type' => 'application/vnd.retailer.v3+json'),
                        $headers
                    ),
                );
                break;
            default:
                throw new \InvalidArgumentException("{$method} is not a supported request method");
                break;
        }
    }
}
