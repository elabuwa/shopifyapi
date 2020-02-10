# elabuwa/shopifyapi

This is a simple wrapper for Shopify's APIs.
This will initially focus on GET requests on the customer and product APIs and move to build on other endpoints as well.
PRs are most welcome.

### Installation

A simple composer install is all you need to get things running.

```sh
$ composer require elabuwa/shopifyapi
```

### Development
The response is a Guzzle Response object

```php
$credentials => [
    'userName' => env('SHOPIFY_USERNAME', ''),
    'password' => env('SHOPIFY_PASSWORD', ''),
    'apiVersion' => env('SHOPIFY_API_VERSION', ''),
    'storeShopifyUrl' => env('SHOPIFY_URL', '')
];
$customer = new shopifyCustomers($credentials);
/** @var http\Client\Response $res */
$response = $customer->customerInfo('customerID');
echo $response->getBody();
echo  $response->getStatusCode();
```
You can use anything that's available at http://docs.guzzlephp.org/en/stable/quickstart.html#using-responses

### Plugins

Guzzle is the only plugin used at the moment.
Guzzle by default has an option to throw exceptions on http error codes.
However, this has been disabled to allow you to handle errors the way you see fit.
If you wish to enable this, simply set `$http_errors = true`.


### Todos
 - Add extra endpoints
 - Write Tests

License
----

MIT
