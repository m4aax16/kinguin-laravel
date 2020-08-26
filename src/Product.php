<?php

namespace m4aax16\kinguin;

use m4aax16\kinguin\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class Product extends Client
{

    const baseUrl = "https://api2.kinguin.net/integration/v1/products";
    
    public static function getPage(?int $page = null)
    {
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

    public static function getPagesCount(?int $limit = 25)
    {

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

    public static function getProduct($productId)
    {
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
}