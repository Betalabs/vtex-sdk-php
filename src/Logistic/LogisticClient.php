<?php

namespace Vtex\Logistic;

use Vtex\VtexClient;

/**
 * @method array freightValues(array $pathParams = [], array $queryParams = [])
 * @method array retrieveBlockedDeliveryWindows(array $pathParams = [], array $queryParams = [])
 * @method array allDocks(array $pathParams = [], array $queryParams = [])
 * @method array dockById(array $pathParams = [], array $queryParams = [])
 * @method array allWarehouses(array $pathParams = [], array $queryParams = [])
 * @method array warehouseById(array $pathParams = [], array $queryParams = [])
 * @method array inventoryBySku(array $pathParams = [], array $queryParams = [])
 * @method array inventoryperwarehouse(array $pathParams = [], array $queryParams = [])
 * @method array inventoryperdock(array $pathParams = [], array $queryParams = [])
 * @method array inventoryperdockandwarehouse(array $pathParams = [], array $queryParams = [])
 * @method array getinventorywithdispatchedreservations(array $pathParams = [], array $queryParams = [])
 * @method array getSupplyLots(array $pathParams = [], array $queryParams = [])
 * @method array holidayById(array $pathParams = [], array $queryParams = [])
 * @method array allHolidays(array $pathParams = [], array $queryParams = [])
 * @method array reservationById(array $pathParams = [], array $queryParams = [])
 * @method array reservationbyWarehouseandSku(array $pathParams = [], array $queryParams = [])
 * @method array getById(array $pathParams = [], array $queryParams = [])
 * @method array getpaged(array $pathParams = [], array $queryParams = [])
 * @method array pagedPolygons(array $pathParams = [], array $queryParams = [])
 * @method array polygonbyId(array $pathParams = [], array $queryParams = [])
 */
class LogisticClient extends VtexClient
{

}