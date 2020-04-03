<?php
namespace shopifyApi;

use GuzzleHttp\Psr7\Response;
use shopifyApi\shopifyApiCore;
use shopifyApi\shopifyProducts;

class shopifyVariants extends shopifyApiCore{

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

    public function getVariantAndProductInfo($variantId)
    {
        $response = $this->getVariantInfo($variantId);
        $responseBody = json_decode($response->getBody(), true);
        if(array_key_exists('variant', $responseBody)){
            $variant = $responseBody['variant'];
            $productId = $variant['product_id'];
            $proObj = new shopifyProducts($this->userName, $this->password, $this->storeShopifyUrl, $this->apiVersion);
            $prodResponse = $proObj->getProductInfo($productId);
            $prodBody = json_decode($prodResponse->getBody(), true);
            $product = $prodBody['product'];
            $data['variant'] = $variant;
            $data['product'] = $product;
            //Todo : send guzzle response object for uniformity
            return json_encode($data);
        } else {
            return null;
        }

    }

    /**
     * 
     * Update stock for an inventory item id
     * If you need to update for a particular variant, retrieve the variant data first.
     * The data holds an inventory_item_id value.
     * 
     */

    public function updateVariantStock($inventoryItemId, $location, $newQty)
    {
        $data['location_id'] = $location;
        $data['inventory_item_id'] = $inventoryItemId;
        $data['available'] = $newQty;

        $this->queryUrl = $this->baseUrl . "inventory_levels/set.json";
        $response = $this->postData($data);
        return $response;
    }
}