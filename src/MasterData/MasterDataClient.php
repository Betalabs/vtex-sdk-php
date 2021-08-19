<?php

namespace Vtex\MasterData;

use Vtex\VtexClient;

/**
 * @method array getdocument(array $pathParams = [], array $queryParams = [])
 * @method array searchdocuments(array $pathParams = [], array $queryParams = [])
 * @method array scrolldocuments(array $pathParams = [], array $queryParams = [])
 * @method array getschemas(array $pathParams = [], array $queryParams = [])
 * @method array getschemabyname(array $pathParams = [], array $queryParams = [])
 * @method array getindices(array $pathParams = [], array $queryParams = [])
 * @method array getindicebyname(array $pathParams = [], array $queryParams = [])
 * @method array listversions(array $pathParams = [], array $queryParams = [])
 * @method array getversion(array $pathParams = [], array $queryParams = [])
 */
class MasterDataClient extends VtexClient { }