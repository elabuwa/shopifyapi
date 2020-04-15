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
    public function __construct($userName,$password,$shopifyUrl,$apiVersion)
    {
        $this->userName = $userName;
        $this->password = $password;
        $this->storeShopifyUrl = $shopifyUrl;
        $this->apiVersion = $apiVersion;
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


}