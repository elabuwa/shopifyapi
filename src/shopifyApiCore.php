<?php
namespace shopifyApi;

use GuzzleHttp\Client;
use Exception;
use http\Params;

class shopifyApiCore {
    /**
     * @var string
     */
    protected $userName;

    /**
     * @var string
     */
    protected $password;
    
    /**
     * @var string
     */
    protected $storeShopifyUrl;
    
    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $queryUrl;

    /** @var string */
    protected $accessToken;

    /**
     * Shopify updates their version from time to time.
     * The API version you wish to use can be changed by updating this propery.
     *
     * @var string
     */
    protected $apiVersion;

    /***
     * Set true if you need Exception errors thrown in for HTTP Protocol errors such as 4xx,5xx
     * http://docs.guzzlephp.org/en/stable/request-options.html#http-errors
     * Comes true by default
     * @var boolean
    */
    protected $http_errors = false;

    public function __construct()
    {
        $this->setUpBaseUrl();
    }

    /**
     * @return void
     */

    private function setUpBaseUrl()
    {
        $this->verifyCredentials();
        if(isset($this->accessToken)){
            $this->baseUrl = "https://" .  $this->storeShopifyUrl . "/admin/api/" . $this->apiVersion . "/";
        } else {
            $this->baseUrl = "https://" . $this->userName . ":" . $this->password . "@" . $this->storeShopifyUrl . "/admin/api/" . $this->apiVersion . "/";
        }

    }

    /**
     * @return void
     */
    private function verifyCredentials()
    {

        if(!isset($this->storeShopifyUrl)){
            throw new Exception("Missing  Store URL");
        }
        if(!isset($this->apiVersion)){
            throw new Exception("Missing  API Version");
        }
        if(!isset($this->accessToken)){
            if(!isset($this->userName) || !isset($this->password)){
                throw new Exception("Missing Username or Password");
            }
        }
        if(!isset($this->accessToken)) {
            throw new Exception("Access Token Missing. Either user shopify api username and key or accessToken to conect");
        }

    }

    /**
     * @param array $queryParams
     * @param array $headers
     * @return object|GuzzleHttp\Client
     */
    protected function getData($queryParams = [], $headers = [])
    {
        $this->verifyCredentials();
        $client = new Client;
        if(isset($this->accessToken)){
            $headers['X-Shopify-Access-Token'] = $this->accessToken;
        }
        $response = $client->request('GET', $this->queryUrl, [
            'query' => $queryParams,
            'headers' => $headers,
            'http_errors' => $this->http_errors
        ]);
        return $response;
    }

    protected function postData($postData, $headers = [])
    {
        $this->verifyCredentials();
        $client = new Client;
        if(isset($this->accessToken)){
            $headers['X-Shopify-Access-Token'] = $this->accessToken;
        }
        $response = $client->request('POST', $this->queryUrl, [
            'json' => $postData,
            'headers' => $headers,
            'http_errors' => $this->http_errors
        ]);
        return $response;
    }
}