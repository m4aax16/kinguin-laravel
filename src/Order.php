<?php

namespace m4aax16\kinguin;

use m4aax16\kinguin\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class Order extends Client
{   
    
    private $apiVersion = "v1";
    const baseUrl = "https://gateway.kinguin.net/esa/api/";
    const endpoint = "order";
    
    function __construct()
    {

    }

    public function setApiVersion($version)
    {
        $this->apiVersion = $version;
    }

    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    //1. place order
    public static function placeOrder($kinguinId,$qty,$price,$couponCode = null)
    {
        try{
            $apiKey = Client::getKey();

            $http= new GuzzleClient();

            //Force using API V1
            $this->setApiVersion("v1");

            $requestUrl = self::baseUrl.'/'.$this->apiVersion.'/'.self::endpoint;

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


            /* Add CouponCode
               For more information visit : https://github.com/kinguinltdhk/Kinguin-eCommerce-API/blob/master/features/CouponCode.md
            */

            if(!empty($couponCode)){
                $orderArr['couponCode'] = $couponCode;
            }
            
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

            //Force using API V1
            $this->setApiVersion("v1");

            $requestUrl = self::baseUrl.'/'.$this->apiVersion.'/'.self::endpoint.'/dispatch';
            
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
    public static function getOrderKeys($dispatchId) // By dispatchId
    {
        try{
            $apiKey = Client::getKey();

            $http= new GuzzleClient();

            //Force using API V1
            $this->setApiVersion("v1");

            $requestUrl = self::baseUrl.'/'.$this->apiVersion.'/'.self::endpoint.'/dispatch/keys?dispatchId='.$dispatchId;

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

    //4. get order keys
    public static function getOrderKeyById($orderId)
    {
        try{
            $apiKey = Client::getKey();
            $http= new GuzzleClient();

            //Force using API V2
            $this->setApiVersion("v2");

            $requestUrl = self::baseUrl.'/'.$this->apiVersion.'/'.self::endpoint.'/'.$orderId.'/keys';

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