# Still in development!

### Create an Catalog client

#### Argument Options
* pathParams
* queryParams
* body

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
    $catalogClient->getAttachment([
        'pathParams' => [
            'attachmentid' => 1
        ]
    ]);
} catch (\Vtex\Exception\VtexException $vtexException) {
    echo $vtexException->getMessage();
}
```