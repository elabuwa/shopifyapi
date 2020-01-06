<?php
namespace shopifyApi;

use shopifyApi\shopifyApiCore;

class shopifyVariants extends shopifyApiCore{

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
     * Retrieve all variants for product
     *
     * @param string $productId
     * @return array
     */

    public function getAllVariants($productId)
    {
        $this->queryUrl = $this->baseUrl . "products/" . $productId . "/variants.json";
        $response = $this->getData();
        return $response;
    }

    /**
     * Retrieve variant information
     *
     * @param string $variantId
     * @return array
     */
    public function getVariantInfo($variantId)
    {
        $this->queryUrl = $this->baseUrl . "variants/" . $variantId . ".json";
        $response = $this->getData();
        return $response;
    }
}