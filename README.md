This is a simple client built on top of Guzzle.

### Usage

#### Initialize by calling:

```php
$client = new Client([
    'code' => MERCHANT_CODE,
    'key' => MERCHANT_APIKEY,
    'base_uri' => 'https://api.avangate.com/3.0/'
]);
```

#### Use as a Guzzle client

```php
try {
    $response = $client->get('orders/?StartDate=2015-01-01');
    $orderListing = json_decode($response->getBody()->getContents());
    
    print_r($orderListing);
    
} catch (\GuzzleHttp\Exception\ClientException $e) {
    $contents = json_decode($e->getResponse()->getBody()->getContents());
    var_dump($contents->message);
    
} catch (\Exception $e) {
    var_dump($e->getMessage());
}
```
