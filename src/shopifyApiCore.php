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

    /** @var bool  */
    protected $responseObj = false;

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
//        if(!isset($this->accessToken)) {
//            throw new Exception("Access Token Missing. Either user shopify api username and key or accessToken to conect");
//        }

    }

    /**
     * @param array $queryParams
     * @param array $headers
     * @return object|GuzzleHttp\Client
     */
    protected function getData($queryParams = [], $headers = [])
    {
        //Can't use 'query' in Guzzle. It encodes the - character, so the shopify response doesn't work
        $this->queryUrl = $this->queryUrl . '?';
        foreach($queryParams as $key => $value){
            $this->queryUrl .= '&' . $key . '=' . $value;
        }
        $this->verifyCredentials();
        $client = new Client;
        if(isset($this->accessToken)){
            $headers['X-Shopify-Access-Token'] = $this->accessToken;
        }
        $response = $client->request('GET', $this->queryUrl, [
            //'query' => $queryParams,
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

    protected function putData($postData, $headers = [])
    {
        $this->verifyCredentials();
        $client = new Client;
        if(isset($this->accessToken)){
            $headers['X-Shopify-Access-Token'] = $this->accessToken;
        }
        $response = $client->request('PUT', $this->queryUrl, [
            'json' => $postData,
            'headers' => $headers,
            'http_errors' => $this->http_errors
        ]);
        return $response;
    }

    protected function deleteData($deleteData = [], $headers = [])
    {
        $this->verifyCredentials();
        $client = new Client;
        if(isset($this->accessToken)){
            $headers['X-Shopify-Access-Token'] = $this->accessToken;
        }
        $response = $client->request('DELETE', $this->queryUrl, [
            'json' => $deleteData,
            'headers' => $headers,
            'http_errors' => $this->http_errors
        ]);
        return $response;
    }

    /**
     * Retrieve Contents for a given url
     * @param string $url
     * @return \Psr\Http\Message\ResponseInterface
     * @throws Exception
     */
    protected function getUrlContents($url)
    {
        $this->verifyCredentials();
        $client = new Client;
        $headers = [];
        if(isset($this->accessToken)){
            $headers['X-Shopify-Access-Token'] = $this->accessToken;
        }
        $response = $client->request('GET', $url, [
            'http_errors' => $this->http_errors,
            'headers' => $headers,
        ]);
        return $response;
    }

    /**
     * Go through the Link header element and identify the next page link
     * @param string $linkHeader
     * @return string|null
     */
    protected function getNextUrl($linkHeader)
    {
        if(is_string($linkHeader)){
            $links = explode(',', $linkHeader);
        } else {
            if(is_array($linkHeader)) {
                $links = explode(',', $linkHeader[0]);
            } else {
                return null;
            }
        }

        $nextStr = '';
        foreach ($links as $link){
            if(stripos($link, 'rel="next"' ) !== false){
                $nextStr = $link;
                break;
            }
        }
        if($nextStr == ''){
            return null;
        } else {
            $nextStr = str_replace('<', '', $nextStr);
            return substr($nextStr, 0, stripos($nextStr, '>'));
        }
    }

    /**
     * Go through all paginated links and retrieve data
     * @param string $nextUrl
     * @param string $keyName
     * @return array
     */
    protected function getPaginatedResults($nextUrl, $keyName)
    {
        $responseData = [];
        $responseHeader = [];
        $hasNextLink = true;
        $i = 0;
        while($hasNextLink) {
            if (!$this->accessToken) {
                //Add the username,password to the URL if access token is not present
                // Todo : parse_url doesn't identify host. Find out reason
                $nextUrl = trim(str_replace($this->storeShopifyUrl, $this->userName . ":" . $this->password . '@' . $this->storeShopifyUrl, $nextUrl));
                //$nextUrl = trim(str_replace($this->storeShopifyUrl, $this->userName . ":" . $this->password . '@' . $this->storeShopifyUrl, $nextUrl));

            }
            if(filter_var($nextUrl, FILTER_VALIDATE_URL) === false){
                return ['data' => $responseData, 'headers' => $responseHeader];
            }

            $resp = $this->getUrlContents($nextUrl);

            $tempBody = json_decode($resp->getBody(), true);
            $responseData = array_merge($responseData, $tempBody[$keyName]);

            $responseHeader = $resp->getHeaders();
            $headerLink = $responseHeader['Link'];
            $nextUrl = $this->getNextUrl($headerLink);
            if ($nextUrl == null) {
                $hasNextLink = false;
            }
            $i++;
        }
        return ['data' => $responseData, 'headers' => $responseHeader];
    }
}
