<?php

namespace m4aax16\kinguin;

use m4aax16\kinguin\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class Order extends Client
{   
    const baseUrl = "https://gateway.kinguin.net/esa/api/v1/order";
    
    //1. place order
    public static function placeOrder($kinguinId,$qty,$price)
    {
        try{
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

    //2. dispatch order
    public static function dispatchOrder($orderId)
    {

        try{

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

    //3. get order keys
    public static function getOrderKeys($dispatchId)
    {
        try{
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