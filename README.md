# Still in development!

### Install SDK
```shell
composer require juanfeltrin/vtex-sdk-php
```

### Create a Catalog Client

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
    /**
     * Argument Options:
     * pathParams
     * queryParams
     * body
    */
    $catalogClient->getAttachment([
        'pathParams' => [
            'attachmentid' => 1
        ]
    ]);
} catch (\Vtex\Exception\VtexException $vtexException) {
    echo $vtexException->getMessage();
}
```