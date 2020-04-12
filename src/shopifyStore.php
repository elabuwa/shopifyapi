<?php
namespace shopifyApi;

use shopifyApi\shopifyApiCore;

class shopifyCustomers extends shopifyApiCore{

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
     * Retrieve store information
     *
     * @return void
     */
    public function storeInformation()
    {
        $this->queryUrl = $this->baseUrl . "shop.json";
        $response = $this->getData();
        return $response;
    }

}