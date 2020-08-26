<?php

namespace m4aax16\kinguin;

use m4aax16\kinguin\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class User extends Client
{
    const baseUrl = "https://api2.kinguin.net/integration/v1/balance";
    
    public static function getBalance()
    {
        try{
            $apiKey = Client::getKey();

            $http= new GuzzleClient();

            $requestUrl = self::baseUrl;
            
            $response = $http->request('GET', $requestUrl, [
                'headers' => [
                    'api-ecommerce-auth' => $apiKey,
                    'Content-Type' => 'application/json'
                ],
            ]); 
        
            return $responseData = json_decode($response->getBody(), true);
        }
        catch(GuzzleException $e)
        {
            return $e;
        }
    }
}