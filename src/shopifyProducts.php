<?php
namespace shopifyApi;

use GuzzleHttp\Psr7\Response;
use shopifyApi\shopifyApiCore;

class shopifyProducts extends shopifyApiCore{

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
     * Retrieve all products from store
     * Send params array to define extra search params
     * 
     * Todo : Read response header to get additional pages where product count is more than 250
     *
     * @param array $params
     * @return void
     */
    public function retrieveAllProducts($params = [])
    {
        if(!array_key_exists('limit', $params)){
            //If no limit has been defined, grab 250 (the maximum given by Shopify for a request)
            $params['limit'] = 250;
        }
        $this->queryUrl = $this->baseUrl . "products.json";
        /** @var Response $response */
        $response = $this->getData($params);
        return json_decode($response->getBody(), true);
    }

    /**
     * Get Product Information
     * @param string $productId
     * @return arrau
     */
    public function getProductInfo($productId)
    {
        $this->queryUrl = $this->baseUrl . "products/" . $productId . ".json";
        /** @var Response $response */
        $response = $this->getData();
        return json_decode($response->getBody(), true);
    }


    /**
     * Get Metafields for Product
     * Todo : Fix the 250 limit issue
     * @param string $productId
     * @return array
     */
    public function getProductMetaFields($productId)
    {
        $params['limit'] = 250;
        $this->queryUrl = $this->baseUrl . "products/" . $productId . "/metafields.json";
        /** @var Response $response */
        $response = $this->getData($params);
        return json_decode($response, true);
    }
}