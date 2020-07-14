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
     * Retrieve all customers from store
     * Send params array to define extra search params
     *
     * @param array $params
     * @return void
     */
    public function retrieveCustomers($params = [])
    {
        $this->queryUrl = $this->baseUrl . "customers.json";
        $response = $this->getData($params);
        return $response;
    }

    /**
     * Search for customers in store
     * Send params array to define extra search params
     *
     * @param array $params
     * @return void
     */
    public function searchCustomers($params = [])
    {
        $queryString = '';
        foreach($params as $key=>$value){
            $queryString.= $key . ':' . $value . ';';
        }
        $queryString = rtrim($queryString, ';');
        $this->queryUrl = $this->baseUrl . "customers/search.json?query=" . $queryString;
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

    public function sendInviteEmail($customerId)
    {
        $this->queryUrl = $this->baseUrl . "customers/" . $customerId . "/send_invite.json";
        $response = $this->getData();
        return $response;
    }

    public function createCustomer($data)
    {
        $this->queryUrl = $this->baseUrl . "customers.json";
        $response = $this->postData($data);
        return $response;
    }

    public function updateCustomer($customerId, $data)
    {
        $this->queryUrl = $this->baseUrl . "customers/$customerId.json";
        $response = $this->putData($data);
        return $response;
    }

}