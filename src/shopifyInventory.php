<?php
namespace shopifyApi;

use GuzzleHttp\Psr7\Response;
use shopifyApi\shopifyApiCore;

class shopifyInventory extends shopifyApiCore{

    /**
     * The construct function. send credentials provided by Shopify to instantiate object
     *
     * @param string $credentials
     */
    private $credentials = [];

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
        $this->credentials = $credentials;
        parent::__construct();
    }

    /**
     * Retrieve all locations
     *
     * @return array
     */

    public function getAllLocations()
    {
        $this->queryUrl = $this->baseUrl . "locations.json";
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
     * Retrieve single location information
     *
     * @param string $locationId
     * @return array
     */
    public function getLocationInfo($locationId)
    {
        $this->queryUrl = $this->baseUrl . "locations/" . $locationId . ".json";
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
     * Get count of locations
     * @return array|null
     */
    public function getLocationCount()
    {
        $this->queryUrl = $this->baseUrl . "locations/count.json";
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
     * 
     * Get list of all inventory for a locatin by it's ID
     * @param string $locationId
     * @return array
     */

    public function getLocationInventory($locationId)
    {
        $this->queryUrl = $this->baseUrl . "locations/" . $locationId . "/inventory_levels.json";
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
     *
     * Update Inventory Qrt
     * @param string $locationId
     * @param string $itemId
     * @param integer $qty
     * @return array
     */

    public function updateInventory($locationId, $itemId, $qty)
    {
        $data = [
            'inventory_item_id' => $itemId,
            'location_id' => $locationId,
            'available_adjustment' => $qty
        ];
        $this->queryUrl = $this->baseUrl . "inventory_levels/adjust.json";
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
     *
     * Update Inventory Item
     * @param string $inventoryTtemId
     * @param array $data
     * @return array
     */

    public function updateInventoryItem($inventoryTtemId, $data)
    {
        $this->queryUrl = $this->baseUrl . "inventory_items/$inventoryTtemId.json";
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