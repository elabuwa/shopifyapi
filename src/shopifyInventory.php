<?php
namespace shopifyApi;

use GuzzleHttp\Psr7\Response;
use shopifyApi\shopifyApiCore;

class shopifyInventory extends shopifyApiCore{

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
     * Retrieve all locations
     *
     * @return array
     */

    public function getAllLocations()
    {
        $this->queryUrl = $this->baseUrl . "locations.json";
        /** @var Response $response */
        $response = $this->getData();
        return $response;
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
       return $response;
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
        return $response;

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
        return $response;
    }

    /**
     *
     * Update Inventory Qty
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
        return $response;
    }

}
