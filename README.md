# Still in development!

### Create an Catalog client and get product by id

```php
<?php
require 'vendor/autoload.php';

$catalogClient = new \Vtex\Catalog\CatalogClient([
    'credentials' => [
        'accountName' => "your account name",
        'environment' => "your environment",
        'appKey' => 'your app key',
        'appToken' => 'your app token',
    ]
]);

try {
    $catalogClient->getProductbyid(['productId' => 6]);
} catch (\Vtex\Exception\VtexException $vtexException) {
    echo $vtexException->getMessage();
}
```