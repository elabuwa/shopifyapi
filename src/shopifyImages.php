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
    public function __construct($userName,$password,$shopifyUrl,$apiVersion)
    {
        $this->userName = $userName;
        $this->password = $password;
        $this->storeShopifyUrl = $shopifyUrl;
        $this->apiVersion = $apiVersion;
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