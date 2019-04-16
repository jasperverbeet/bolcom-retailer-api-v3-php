<?php

namespace BolRetailerAPI\Client;

use GuzzleHttp\Psr7\Response;

/**
 *  Base Client
 *
 *  The base client
 *
 *  @author Jasper Verbeet
 */
class BaseClient
{

   const TOKEN_ENDPOINT = 'https://login.bol.com/token';
   const API_ENDPOINT = 'https://api.bol.com/';

   private $client;

   function __construct()
   {
      $this->client = new \GuzzleHttp\Client([
         'http_errors' => false
      ]);
   }

   /**
    * Perform a GET request.
    * @param $url string endpoint
    * @param $query string query parameters
    * @param $headers array headers
    */
   public function get(string $url, array $query = array(), array $headers = array()): Response
   {
      return $this->client->request('GET', $url, [
         'query' => $query,
         'headers' => $headers
      ]);
   }

   /**
    * Perform a POST request.
    * @param string $url Endpoint to do the request
    * @param array $parameters Optional body parameters
    * @param array $headers Optional headers
    */
   public function post(string $url, array $parameters = array(), array $headers = array()): Response
   {
      return $this->client->request('POST', $url, [
         'form_params' => $parameters,
         'headers' => $headers
      ]);
   }
}
