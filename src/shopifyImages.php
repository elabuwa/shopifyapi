<?php
namespace shopifyApi;

use shopifyApi\shopifyApiCore;

class shopifyImages extends shopifyApiCore{

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

    public function uploadImageFromUrl($productId, $src, $mainImage = false)
    {
        $data['image']['src'] = $src;
        if($mainImage != false){
            $data['image']['position'] = 1;
        }
        $this->queryUrl = $this->baseUrl . "products/" . $productId . "/images.json";
        $response = $this->postData($data);
        return $response;
    }
}