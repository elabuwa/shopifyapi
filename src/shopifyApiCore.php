<?php
namespace shopifyApi;

use GuzzleHttp\Client;

class shopifyApiCore {
    protected $userName;
    protected $password;
    protected $storeShopifyUrl;
    protected $baseUrl;
    protected $queryUrl;

    /***
     * Set true if you need Exception errors thrown in for HTTP Protocol errors such as 4xx,5xx
     * http://docs.guzzlephp.org/en/stable/request-options.html#http-errors
     * Comes true by default
    */

    protected $http_errors = false;

    public function __construct()
    {
        $this->setUpBaseUrl();
    }

    private function setUpBaseUrl()
    {
        $this->verifyCredentials();
        $this->baseUrl = "https://" . $this->userName . ":" . $this->password . "@" . $this->storeShopifyUrl . "/admin/api/2019-10/";
    }

    private function verifyCredentials()
    {
        if(!isset($this->userName) || !isset($this->password) || !isset($this->storeShopifyUrl)){
            throw new Exception("Missing Credential Or Store URL");
        }
    }

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
}