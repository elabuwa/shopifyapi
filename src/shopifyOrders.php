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
}