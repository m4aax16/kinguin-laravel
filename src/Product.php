<?php

namespace m4aax16\kinguin;

use m4aax16\kinguin\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class Product extends Client
{   
    const baseUrl = "https://gateway.kinguin.net/esa/api/v1/products";

    public static function getPage(?int $page = null)
    {
        try{
            //Build the client
            $apiKey = Client::getKey();

            $http= new GuzzleClient();

            $requestUrl = self::baseUrl;

            if(!empty($page)){
                $requestUrl = $requestUrl.'?page='.$page;
            }
            
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

    public static function getPagesCount(?int $limit = 25)
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

            $responseNew =  json_decode((string) $response->getBody(), true);

            $totalProducts = $responseNew['item_count'];
            
            $totalPages = ceil($totalProducts / $limit);
            
            return $totalPages;
            
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

    public static function getProduct($productId)
    {   
        try{

        $apiKey = Client::getKey();

        $http= new GuzzleClient();

        $requestUrl = self::baseUrl.'/'.$productId;
        
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