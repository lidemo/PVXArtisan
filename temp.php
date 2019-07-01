<?php

/* $pvxQuery = [
    "date"  => [
        "from"  => "21/06/2019",
        "to"    => "25/06/2019",
        "between"   => "21/06/2019|25/06/2019"
    ],
    "orderNumber"   => [
        "="     => "W567432-1D-N",
        "!="    => "Wjasjda"
    ],
    "itemSku"       => [
        "=" => "324564"
    ],
    "from"          => "R.A4.B1",
];


$pvxQuery = "[DateTimestamp] >= DateTime(2019,09,10,00,00,00)"; */

require_once 'vendor/autoload.php';


/* $query1 = PVXArtisan\QueryBuilder\PVXQueryBuilder::datetime("21/06/2019", "25/06/2019", "Date timestamp")
        ->and()
        ->condition("orderNumber", "=", "W5343-1d-n")
        ->and()
        ->condition("from", "!=", "R.A5.1.D")
        ->or()
        ->openBracket()
        ->condition("to", "!=", "R.d.d")
        ->or()->condition("to", "contains", "bla")
        ->closeBracket()->getQuery();

$query2 = PVXArtisan\QueryBuilder\PVXQueryBuilder::datetime("24/06/2019 10:00", "24/06/2019 11:00", 'Date timestamp')
        ->getQuery(); */

$template = new PVXArtisan\Templates\ItemMovementHistory();
$queryBuilder = new PVXArtisan\QueryBuilder\PVXQueryBuilder();
$query3 = $queryBuilder->linkTemplate($template)->datetime("24/06", "25/06")->getQuery();

/* echo $query1 . '<br />';
echo $query2 . '<br />'; */
echo $query3 . '<br /><br />'; 


$pvxApiAuth = new PVXArtisan\PvxApiAuth('rng2744', 'ggeorgiev', 'goper');
$pvxApiAuth->authenticate();



//echo $data;

$query4 = PVXArtisan\QueryBuilder\PVXQueryBuilder::type(PVXArtisan\Types\ReportType::getInstance())->datetime("26", "27", "Date timestamp")->getQuery();
/* $query4 = 'SalesOrderNumber ="W9010840-1C-85"'; */
echo $query4 . '<br /><br />';



/* $pvxGetReportObj = new PVXArtisan\PvxGetReport($pvxApiAuth);
$pvxGetReportObj->setSearchClause($query5);
//$pvxGetReportObj->setColumns();
$pvxGetReportObj->setTemplateName("Item movement history");
$data = $pvxGetReportObj->get(); */


///QUERY 5 HERE
/* $query5 = PVXArtisan\QueryBuilder\PVXQueryBuilder::linkTemplate(PVXArtisan\Templates\SalesOrders::getInstance())
    ->datetime('26/06/2019')
    ->and()
    ->condition('order', "=", "W8973301-1D-T")
    ->and()
    ->getQuery();


echo $query5;

$pvxGetData = new PVXArtisan\PvxGetData($pvxApiAuth);
$pvxGetData->setSearchClause($query5);
$pvxGetData->setTemplateName("Sales orders");
$data = $pvxGetData->get();

echo $data; */

$pvxImportData = new PVXArtisan\PvxImportData($pvxApiAuth);

/* $csvHeader = 'ItemCode,Name,Barcode,Description,ItemGroup,UnitOfMeasure,DefaultEconomicOrderQuantity,DefaultLeadTime,DefaultSuppliersPartNumber,HasSerialNumbers,UseManufacturersSerialNumber,ReorderPoint,Traceability,ShelfLife,DefaultNumberItemsPerContainer,DefaultContainerType,DefaultNumberItemsPerOuterCase,DefaultNumberItemsPerInnerCase,BuyPrice,WholesalePrice,RetailPrice,Weight,WeightMeasure,Height,Width,Depth,DimensionMeasure,Tags,Attribute1,Attribute2,Attribute3,Attribute15' . PHP_EOL . '';
$csvBody =  */
$result = $pvxImportData->getSaveTemplate('Item types');
echo 'try4';

echo $result;




