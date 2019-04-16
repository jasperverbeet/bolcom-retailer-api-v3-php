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

   public $client;

   function __construct()
   {
      $this->client = new \GuzzleHttp\Client();
   }

   /**
    * Returns an array with default headers
    */
   private function getDefaultHeaders() : array
   {
      return array(
         'Accept' => 'application/vnd.retailer.v3+json',
      );
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
         'headers' => array_merge(
            $headers,
            $this->getDefaultHeaders()
         ),
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
         'body' => json_encode($parameters),
         'headers' => array_merge(
            $headers,
            $this->getDefaultHeaders(),
         ),
      ]);
   }
}
