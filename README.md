# m4aax16/kinguin

## Description

##### m4aax16/kinguin-laravel is a unofficial Kinguin api wrapper for Laravel

## Installation

``` composer require m4aax16/kinguin-laravel "^1.1-alpha" ```

## Functions
	Product:
		- GET products per page
		- GET count of pages
		- GET single product
	
	Region:
		- GET all regions
		- GET one region by Id
	
	User:
		- GET user balance
	
	Order:
		- Place order
		- Dispatch order
		- Get order keys
	Handle Exceptions


## Usage

### Initalize the objects

```php 
use m4aax16\kinguin\Client;
use m4aax16\kinguin\Product;
use m4aax16\kinguin\Order;
use m4aax16\kinguin\User;
use m4aax16\kinguin\Region; 

```

## Client

### Client initialization
```php
 $kinguinClient = new Client("YOUR-API-KEY-HERE");
```

## Product

### GET Products by page number 
```php

//This function will fetch 25 products per Page, it's a default value by Kinguin

 $page = 1; 
 $products = Product::getPage($page); // $page is NOT required, default is $page = 1
 
 return $products;

```
###### This function will output a JSON object of products

#### Accessing the fetched result
```php

foreach($products['results'] as $product)
{
	$product['kinguinId']; //Accessing product attribute Id => Output : INT
	$product['name']; //Accessing product attribute name => Output : STRING
	
	//accessing other objects of product
	//Example : Screenshots
	$screenshots = $product['screenshots'];
	
	foreach($screenshots as $screenshot)
	{
		$screenshot['url_original'];
	}
}

```


### GET count of pages 
```php
$itemsPerPage = 100; // Max. products per page is 100
$pageCount = Product::getPagesCount($itemsPerPage); // $itemsPerPage is NOT required, default $itemsPerPage = 25

```

### GET a single product

```php
$productId = 5; //set the kinguinId of product

$product = Product::getProduct($productId); //returns detailed information about this product

```

## Region

### GET all regions

##### Note : The output isn't a call to the Kinguin API Endpoint.
##### All regions are saved as a array at ``` Region::class ```

```php
Region::getRegions(); //returns all regions, the array key is the id of the region
```

#### Accessing the Id and name
```php
$regions = Region::getRegions();

foreach($regions as $regionId => $value)
{
	echo $regionId; //region id
	echo " ";
	echo $value; //region name
	echo "</br>";
}

```

### GET Region by ID

```php
$regionId = 1; // Id 1 is "Europe"
$region = Region::getRegionById($regionId); 

echo $region; //Output will be a STRING
```

## User

### GET User Balance
```php
$balance = User::getBalance(); // Output is a FLOAT, Example: 20439.99

```

## Order
##### Note : The Kinguin-Endpoint supports multiple items on one order
##### But this package doesn't support multiple items per order

### Place order

```php
$productId = 195; // ProductId is Warhammer for Steam
$qty = 1;
$price = 0.59;

//All parameter all required
$order = Order::placeOrder($productId,$qty,$price,$couponCode*);  // returns a json with order "orderId: 26983294"
								  // $couponCode is a optional parameter

```
### Dispatch order
```php
$dispatch = Order::dispatchOrder($orderId);    //returns:  "dispatchId:	19036724"
$dispatchId = $dispatch['dispatchId'];
```

### GET ordered keys
```php
$keys = Order::getOrderKeys($dispatchId);

/* Example Output :
        0:
        serial	"SOME-ACTIVATION-KEY"
        type	"text/plain" //Key types are text or image
        name	"Warhammer 40,000: Space Marine Steam CD Key"
        kinguinId	195
*/


```
Note : 
To save your own and Kinguin server resources, it's highly recommend subscribing to order postback.
It's also much more efficient. Replace your storeId if you want to use this url :
https://www.kinguin.net/integration/dashboard/stores/{storeId}/postback

As soon as the Post Back notification arrives, this function can be used : 

```php
$kgOrderId = "9XQ3FFRQKVL";
$keys = Order::getOrderKeyById($kgOrderId);

/* Example Output :
	0	
	serial	"Real_1942  XYYG8-4GBCC-ILMFM"
	type	"text/plain"
	name	"Real 1942 Steam CD Key"
	kinguinId	57083
	productId	"5c9b787a2539a4e8f184354a"
	offerId	"5fa56a6d9c2506000196e12a"
*/

```




### Handle Exceptions

Note : It works on all methods except on Region::class

```php
	
   $productId = 0; //set the kinguinId of product

    $product = Product::getProduct($productId); //returns detailed information about this product

    //Check the method getStatusCode exists
    if(method_exists($product,'getStatusCode')){
      //If the status code is higher than 200
      if($product->getStatusCode() > 200){
         
        //This method return the full error message inline
        $errFull = $product->getData()->error;

        //This method return only the Kinguin Error Code
        $errCode = $product->getData()->response->code;
        
        //This method return the error message
        $errMessage = $product->getData()->response->message;

        echo "Full Error : ".$errFull."</br>";
        echo "Error Code : ".$errCode."</br>";
        echo "Error Message : ".$errMessage."</br>";

        /* Example ouputs : 
                1. If the client is not authorized =>

                Full Error : Client error: `GET https://gateway.kinguin.net/esa/api/v1/products/0` resulted in a `401 Unauthorized` response: {"code":2401,"message":"Full authentication is required to access this resource."}
                Error Code : 2401
                Error Message : Full authentication is required to access this resource.

                2. If the client is authorized but something goes wrong. For example the requested product does not exist =>

                Full Error : Client error: `GET https://gateway.kinguin.net/esa/api/v1/products/0` resulted in a `404 Not Found` response: {"code":3404,"message":"Product not found"}
                Error Code : 3404
                Error Message : Product not found
        */

      }
    }
    else{
       //Do something clever with the requested product
       return $product;

    }


```
