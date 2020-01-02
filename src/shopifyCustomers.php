<?php
namespace elabuwa\ShopifyApi;

use ShopifyApi\shopifyApiCore;

class shopifyCustomers extends shopifyApiCore{

    public function __construct($userName,$password,$shopifyUrl)
    {
        $this->userName = $userName;
        $this->password = $password;
        $this->storeShopifyUrl = $shopifyUrl;
        parent::__construct();
    }

    public function retrieveCustomers($params = [])
    {
        $this->queryUrl = $this->baseUrl . "customers.json";
        $response = $this->getData($params);
        return $response;
    }

    public function customerCount()
    {
        $this->queryUrl = $this->baseUrl . "customers/count.json";
        $response = $this->getData();
        return $response;
    }

    public function customerInfo($customerId)
    {
        
        $this->queryUrl = $this->baseUrl . "customers/" . $customerId . ".json";
        $response = $this->getData();
        return $response;
    }

    public function customerOrders($customerId)
    {
        
        $this->queryUrl = $this->baseUrl . "customers/" . $customerId . "/orders.json";
        $response = $this->getData();
        return $response;
    }

}