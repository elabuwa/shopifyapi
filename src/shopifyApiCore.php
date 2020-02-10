<?php
namespace shopifyApi;

use GuzzleHttp\Client;
use Exception;

class shopifyApiCore {
    /**
     * @var string
     */
    public $userName;

    /**
     * @var string
     */
    public $password;
    
    /**
     * @var string
     */
    public $storeShopifyUrl;
    
    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @var string
     */
    public $queryUrl;

    /**
     * Shopify updates their version from time to time.
     * The API version you wish to use can be changed by updating this propery.
     *
     * @var string
     */
    public $apiVersion;

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
        $this->baseUrl = "https://" . $this->userName . ":" . $this->password . "@" . $this->storeShopifyUrl . "/admin/api/" . $this->apiVersion . "/";
    }

    /**
     * @return void
     */
    private function verifyCredentials()
    {
        if(!isset($this->userName) || !isset($this->password) || !isset($this->storeShopifyUrl) || !isset($this->apiVersion)){
            throw new Exception("Missing Credential Or Store URL");
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
        $response = $client->request('POST', $this->queryUrl, [
            'json' => $postData,
            'headers' => $headers,
            'http_errors' => $this->http_errors
        ]);
        return $response;
    }
}