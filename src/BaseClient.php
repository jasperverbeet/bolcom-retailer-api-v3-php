<?php

namespace Jasperverbeet\BolRetailerAPI;

use GuzzleHttp\Psr7\Response;

/**
 *  Client
 *
 *  TODO:
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
      $this->setDefaultHeaders();
   }

   /**
    * Sets the default headers as expected by the bol.com api.
    */
   private function setDefaultHeaders()
   {
      // $this->client->setDefaultOption('headers', array(
      //    'Content-Type' => 'application/x-www-form-urlencoded',
      //    'Accept' => 'application/json',
      // ));
   }

   /**
    * Perform a GET request.
    * @param $url string endpoint
    * @param $query string query parameters
    * @param $headers array headers
    */
   public function get(string $url, array $query = array(), array $headers = array()) : Response
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
    public function post(string $url, array $parameters = array(), array $headers = array()) : Response
   {
      return $this->client->request('POST', $url, [
         'form_params' => $parameters,
         'headers' => $headers
      ]);
   }
}
