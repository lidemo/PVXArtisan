# PVXArtisan
### A Peoplevox WMS API wrapper

Use the Peoplevox API without the hassle, using one-liner queries and easy import statements.


### Examples:

Pulling data from a PVX integration template
```php
/** 
* First authenticate with your PVX issued client id, and your username and password
*/
$pvxAuth = new PVXApiAuth('clientId', 'username', 'password');

/** 
* Pull a single item with ->first() 
* */
$orderData = (new PvxEntity($pvxAuth, 'Sales orders'))->where('SalesOrderNumber','S123')->first(); 

/** 
* Pull a list of items with ->get() 
*/
$data = (new PvxEntity($pvxAuth, 'Sales orders'))->where('SalesOrderNumber', 'not contains', 'S123')->get();
```
<br></br>
 
Pulling data from a PVX Report
```php
$pvxAuth = new PVXApiAuth('clientId', 'username', 'password');

/**
* Pull data with a specified time frame and get only the fields you require
*/
$data = (new PvxReport($pvxAuth, 'Sales order summary')
  ->datetime('01/01/2020 07:00', '02/01/2020 10:00', 'Requested delivery date')
  ->where('Status', '!=', 'Cancelled')
  ->get(['Sales order no.', 'Status']);
```
<br></br>
Importing data into PVX
```php
$pvxAuth = new PVXApiAuth('clientId', 'username', 'password');

$importData = new PvxImportData('Item types');
$importData->add('ItemCode', '123654');
$importData->add('ItemBarcode', '123654');
$importData->save();
```
