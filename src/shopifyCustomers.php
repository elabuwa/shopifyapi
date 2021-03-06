<?php
namespace shopifyApi;

use GuzzleHttp\Psr7\Response;
use shopifyApi\shopifyApiCore;

class shopifyCustomers extends shopifyApiCore{

    /**
     * The construct function. send credentials provided by Shopify to instantiate object
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
     * Retrieve all customers from store
     * Send params array to define extra search params
     *
     * @param array $params
     * @return array
     */
    public function retrieveAllCustomers()
    {
        $params['limit'] = 250;
        $this->queryUrl = $this->baseUrl . "customers.json";
        /** @var Response $response */
        $response = $this->getData($params);

        $headers = $response->getHeaders();

        $linkExist = array_key_exists('Link', $headers);
        if($linkExist) {
            $body = json_decode($response->getBody(), true);
            $headerLink = $headers['Link'];
            $nextUrl = $this->getNextUrl($headerLink);
            $response = $this->getPaginatedResults($nextUrl, 'customers');
            $data = [];
            $data['customers'] = array_merge($body['customers'], $response['data']);

            if($this->responseObj){
                //Return response obj if set to true
                $mockResponse = new Response(200, $response['headers'], $data);
                return $mockResponse;
            } else {
                return $data;
            }
        } else {
            if($this->responseObj){
                //Return response obj if set to true
                return $response;
            } else {
                return json_decode($response->getBody(), true);
            }
        }
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
        /** @var Response $response */
        $response = $this->getData($params);
        if($this->responseObj){
            //Return response obj if set to true
            return $response;
        } else {
            return json_decode($response->getBody(), true);
        }
    }

    /**
     * Retrieve customers from store
     * Send params array to define extra search params
     *
     * @param array $params
     * @return array
     */
    public function retrieveCustomers($params = [])
    {
        if(!array_key_exists('limit', $params)){
            $params['limit'] = 250;
        }
        $this->queryUrl = $this->baseUrl . "customers.json";
        /** @var Response $response */
        $response = $this->getData($params);

        $headers = $response->getHeaders();

        $linkExist = array_key_exists('Link', $headers);
        if($linkExist) {
            $body = json_decode($response->getBody(), true);
            $headerLink = $headers['Link'];
            $nextUrl = $this->getNextUrl($headerLink);
            $response = $this->getPaginatedResults($nextUrl, 'customers');

            $data = [];
            $data['customers'] = array_merge($body['customers'], $response['data']);

            if($this->responseObj){
                //Return response obj if set to true
                $mockResponse = new Response(200, $response['headers'], $data);
                return $mockResponse;
            } else {
                return $data;
            }
        } else {
            if($this->responseObj){
                //Return response obj if set to true
                return $response;
            } else {
                return json_decode($response->getBody(), true);
            }
        }
    }

    /**
     * Retrieve customer count in Shopify Store
     * @return array
     */
    public function customerCount()
    {
        $this->queryUrl = $this->baseUrl . "customers/count.json";
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
     * Retrieve Customer Info
     * @param string $customerId
     * @return array
     */
    public function customerInfo($customerId)
    {
        $this->queryUrl = $this->baseUrl . "customers/" . $customerId . ".json";
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
     * Retrieve all orders for a customer
     * Todo : Investigate pagination for customer orders
     * @param string $customerId
     * @return array
     */
    public function customerOrders($customerId)
    {
        $this->queryUrl = $this->baseUrl . "customers/" . $customerId . "/orders.json";
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
     * Send Invitation Email to customer
     * @param string $customerId
     * @return array
     */
    public function sendInviteEmail($customerId)
    {
        $this->queryUrl = $this->baseUrl . "customers/" . $customerId . "/send_invite.json";
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
     * Create new customer in Shopify
     * @param array $data
     * @return array
     */
    public function createCustomer($data)
    {
        $this->queryUrl = $this->baseUrl . "customers.json";
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
     * Update Shopify Customer
     * @param string $customerId
     * @param array $data
     * @return array
     */
    public function updateCustomer($customerId, $data)
    {
        $this->queryUrl = $this->baseUrl . "customers/$customerId.json";
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