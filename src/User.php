<?php

namespace m4aax16\kinguin;

use m4aax16\kinguin\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class User extends Client
{
    const baseUrl = "https://gateway.kinguin.net/esa/api/v1/balance";
    
    //Try to get Balance of user
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
            //Handle the exception
            $errResponse = json_decode($e->getResponse()->getBody(), true);

            return response()->json([
                'error' => $e->getMessage(),
                'response' => $errResponse
            ], 500);
        }
    }
}