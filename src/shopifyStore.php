<?php
namespace shopifyApi;

use GuzzleHttp\Psr7\Response;
use shopifyApi\shopifyApiCore;

class shopifyStore extends shopifyApiCore{

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
     * Retrieve store information
     *
     * @return array
     */
    public function storeInformation()
    {
        $this->queryUrl = $this->baseUrl . "shop.json";
        /** @var Response $response */
        $response = $this->getData();
        return json_decode($response->getBody(), true);
    }

}