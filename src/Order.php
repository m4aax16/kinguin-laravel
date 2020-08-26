<?php

namespace m4aax16\kinguin;

use m4aax16\kinguin\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class Order extends Client
{
    const baseUrl = "https://api2.kinguin.net/integration/v1/order";
    
    public static function placeOrder($kinguinId,$qty,$price)
    {

        $apiKey = Client::getKey();

        $http= new GuzzleClient();

        $requestUrl = self::baseUrl;

        $apiError = array();

        if(empty($kinguinId)){
            $apiError['message'] = "kinguinId couldn't be empty";
        }
        else if(empty($qty)){
            $apiError['message'] = "qty couldn't be empty";
        }
        else if(empty($kinguinId)){
            $apiError['message'] = "price couldn't be empty";
        }

        if($apiError)
        {
            return response()->json($apiError);
        }
        
        $orderArr['products'][0]['kinguinId'] = $kinguinId;
        $orderArr['products'][0]['qty'] = $qty;
        $orderArr['products'][0]['price'] = $price;

        $response = $http->request('POST', $requestUrl, [
            'headers' => [
                'api-ecommerce-auth' => $apiKey,
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode(
                $orderArr
            ),
        ]); 

        return $responseData = json_decode($response->getBody(), true);

    }

    public static function dispatchOrder($orderId)
    {

        $apiKey = Client::getKey();

        $http= new GuzzleClient();

        $requestUrl = self::baseUrl;   
        
        $requestUrl = $requestUrl.'/dispatch';
        
        $bodyArr['orderId'] = $orderId;

        $response = $http->request('POST', $requestUrl, [
            'headers' => [
                'api-ecommerce-auth' => $apiKey,
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode(
                $bodyArr
            ),
        ]); 

        return $responseData = json_decode($response->getBody(), true);

    }

    public static function getOrderKeys($dispatchId)
    {
        $apiKey = Client::getKey();

        $http= new GuzzleClient();

        $requestUrl = self::baseUrl; 
        
        $requestUrl = $requestUrl.'/dispatch/keys?dispatchId='.$dispatchId;

        $response = $http->request('GET', $requestUrl, [
            'headers' => [
                'api-ecommerce-auth' => $apiKey,
                'Content-Type' => 'application/json'
            ],
        ]);

        return $responseData = json_decode($response->getBody(), true);

    }

}