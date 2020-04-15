<?php
namespace shopifyApi;

use shopifyApi\shopifyApiCore;

class shopifyOrders extends shopifyApiCore{

    /**
     * The construct function. send credentials provided by Shopify to instantiate object
     *
     * @param string $userName
     * @param string $password
     * @param string $shopifyUrl
     * @param string $apiVersion
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
     */ 
    public function retrieveOrder($id)
    {
        $this->queryUrl = $this->baseUrl . "orders/" . $id . ".json";
        $response = $this->getData();
        return $response;
    }

    /**
     * Fulfill an order
     */

    public function fulfillOrder($id, $locationId, $trackingNumber = '', $trackingUrls = [], $notifyCustomer = false){
        
        $this->queryUrl = $this->baseUrl . "orders/" . $id . "/fulfillments.json";

        $data['location_id'] = $locationId;
        $data['tracking_number'] = $trackingNumber;
        $data['tracking_urls'] = $trackingUrls;
        $data['notify_customer'] = $notifyCustomer;
        $payload['fulfillment'] = $data;

        $response = $this->postData($payload);
        return $response;
    }

    public function createOrder($data)
    {
        $this->queryUrl = $this->baseUrl . "orders.json";
        $response = $this->postData($data);
        return $response;
    }

    public function getOrders($data)
    {
        if(!array_key_exists('limit', $data)){
            //If no limit has been defined, grab 250 (the maximum given by Shopify for a request)
            $data['limit'] = 250;
        }
        $this->queryUrl = $this->baseUrl . "orders.json";
        $response = $this->getData($data);
        return $response;
    }

    public function updateOrder($data, $orderId)
    {
        $this->queryUrl = $this->baseUrl . "orders/" . $orderId . ".json";
        $response = $this->putData($data);
        return $response;
    }


}