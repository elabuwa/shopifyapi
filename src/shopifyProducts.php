<?php
namespace shopifyApi;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\MessageInterface;
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
        $headers = $response->getHeaders();

        $linkExist = array_key_exists('Link', $headers);
        if($linkExist) {
            $body = json_decode($response->getBody(), true);
            $headerLink = $headers['Link'];
            $nextUrl = $this->getNextUrl($headerLink);
            $response = $this->getPaginatedResults($nextUrl, 'products');

            $data = [];
            $data['products'] = array_merge($body['products'], $response['data']);

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
     * Get Product Information
     * @param string $productId
     * @return arrau
     */
    public function getProductInfo($productId)
    {
        $this->queryUrl = $this->baseUrl . "products/" . $productId . ".json";
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
        $headers = $response->getHeaders();

        $linkExist = array_key_exists('Link', $headers);
        if($linkExist) {
            $body = json_decode($response->getBody(), true);
            $headerLink = $headers['Link'];
            $nextUrl = $this->getNextUrl($headerLink);
            $response = $this->getPaginatedResults($nextUrl, 'metafields');

            $data = [];
            $data['metafields'] = array_merge($body['metafields'], $response['data']);

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
     * Count Metafields For A Product
     * @param string $productId
     * @return integer
     */
    public function countMetaFields($productId)
    {
        $this->queryUrl = $this->baseUrl . "products/" . $productId . "/metafields/count.json";
        /** @var Response $response */
        $response = $this->getData();

        if($this->responseObj){
            //Return response obj if set to true
            return $response;
        } else {
            return json_decode($response->getBody(), true);
        }
    }


}
