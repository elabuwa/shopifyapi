<?php
namespace shopifyApi;

use GuzzleHttp\Psr7\Response;
use shopifyApi\shopifyApiCore;

class shopifyFulfillment extends shopifyApiCore{

    /**
     * The construct function. send credentials provided by Shopify to instantiate object
     *
     * @param string $credentials
     */
    private $credentials = [];

    public function __construct($credentials = [])
    {
        if(array_key_exists('userName', $credentials)){
            $this->userName = $credentials['userName'];
        }
        if(array_key_exists('password', $credentials)){
            $this->password = $credentials['password'];
        }
        if(array_key_exists('apiVersion', $credentials)){
            $this->apiVersion = $credentials['apiVersion'];
        }
        if(array_key_exists('storeShopifyUrl', $credentials)){
            $this->storeShopifyUrl = $credentials['storeShopifyUrl'];
        }
        if(array_key_exists('accessToken', $credentials)){
            $this->accessToken = $credentials['accessToken'];
        }
        $this->credentials = $credentials;
        parent::__construct();
    }

    /**
     * Fulfill all line items
     *
     * @return array
     */

    public function fulfillAllItems($orderId, $data)
    {
        $this->queryUrl = $this->baseUrl . "/orders/" . $orderId . "/fulfillments.json";
        /** @var Response $response */
        $response = $this->postData($data);
        if($this->responseObj){
            //Return response obj if set to true
            return $response;
        } else {
            return json_decode($response->getBody(), true);
        }
    }

}