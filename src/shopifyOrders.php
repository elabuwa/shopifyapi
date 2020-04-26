<?php
namespace shopifyApi;

use GuzzleHttp\Psr7\Response;
use shopifyApi\shopifyApiCore;

class shopifyOrders extends shopifyApiCore{

    /**
     * The construct function. send credentials provided by Shopify to instantiate object
     *
     * @param array $credentials
     */
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
        parent::__construct();
    }

    /**
     * Retrieve Shopify Order
     * @param string $id
     * @return array
     */ 
    public function retrieveOrder($id)
    {
        $this->queryUrl = $this->baseUrl . "orders/" . $id . ".json";
        /** @var Response $response */
        $response = $this->getData();
        if($this->responseObj){
            //Return response obj if set to true
            return $response;
        } else {
            return json_decode($response->getBody(), true);
        }
    }

    /**
     * Fulfill an order
     * @param string $id
     * @param string $locationId
     * @param string $trackingNumber
     * @param array $trackingUrls
     * @param boolean $notifyCustomer
     * @return array
     */

    public function fulfillOrder($id, $locationId, $trackingNumber = '', $trackingUrls = [], $notifyCustomer = false){
        
        $this->queryUrl = $this->baseUrl . "orders/" . $id . "/fulfillments.json";

        $data['location_id'] = $locationId;
        $data['tracking_number'] = $trackingNumber;
        $data['tracking_urls'] = $trackingUrls;
        $data['notify_customer'] = $notifyCustomer;
        $payload['fulfillment'] = $data;
        /** @var Response $response */
        $response = $this->postData($payload);
        if($this->responseObj){
            //Return response obj if set to true
            return $response;
        } else {
            return json_decode($response->getBody(), true);
        }
    }

    /**
     * Create a shopify Order
     * @param array $data
     * return array
     */
    public function createOrder($data)
    {
        $this->queryUrl = $this->baseUrl . "orders.json";
        /** @var Response $response */
        $response = $this->postData($data);
        if($this->responseObj){
            //Return response obj if set to true
            return $response;
        } else {
            return json_decode($response->getBody(), true);
        }
    }

    /**
     * Retrieve Orders From Shopify
     * Todo : Fix the 250 pagination limit
     * @param array $data
     * @return array
     */
    public function getOrders($data)
    {
        if(!array_key_exists('limit', $data)){
            //If no limit has been defined, grab 250 (the maximum given by Shopify for a request)
            $data['limit'] = 250;
        }
        $this->queryUrl = $this->baseUrl . "orders.json";
        /** @var Response $response */
        $response = $this->getData($data);
        if($this->responseObj){
            //Return response obj if set to true
            return $response;
        } else {
            return json_decode($response->getBody(), true);
        }
    }

    /**
     * Update Shopify Order
     * @param array $data
     * @param string $orderId
     * @return array
     */
    public function updateOrder($data, $orderId)
    {
        $this->queryUrl = $this->baseUrl . "orders/" . $orderId . ".json";
        /** @var Response $response */
        $response = $this->putData($data);
        if($this->responseObj){
            //Return response obj if set to true
            return $response;
        } else {
            return json_decode($response->getBody(), true);
        }
    }


}