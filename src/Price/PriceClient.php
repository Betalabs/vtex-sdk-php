<?php

namespace Vtex\Price;

use Vtex\VtexClient;

/**
 * @method array getPrice(array $pathParams = [], array $queryParams = [])
 * @method array getFixedPrices(array $pathParams = [], array $queryParams = [])
 * @method array getFixedPricesonapricetable(array $pathParams = [], array $queryParams = [])
 * @method array getComputedPricebypricetable(array $pathParams = [], array $queryParams = [])
 * @method array getPricingConfig(array $pathParams = [], array $queryParams = [])
 * @method array getPricingv2ActiveStatus(array $pathParams = [], array $queryParams = [])
 * @method array createrulesforapricetable(array $pathParams = [], array $queryParams = [])
 */
class PriceClient extends VtexClient { }