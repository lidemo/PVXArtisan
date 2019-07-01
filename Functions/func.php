<?php
function integration_export($template,$pageno,$perpage,$search){
	$ns = "http://www.peoplevox.net/";
$clientid = "rng2744"; 
$username = "stats-api-mfc"; 
$password = base64_encode("rave");  
$socket_context = stream_context_create(array('http' => array('protocol_version'  => 1.0)));
$client = new SoapClient("http://wms.peoplevox.net/$clientid/resources/integrationservicev4.asmx?WSDL", array('exceptions' => 0,'stream_context' => $socket_context,'trace' => 1)); 
$date = date("Y,m,d");

// body vars
$someTemplateName = $template;
$somePageNo = $pageno;
$someItemsPerPage = $perpage;
$someSearchClause = $search;

$params = array("clientId"=>$clientid,"username"=>$username,"password"=>$password);
$start = $client->Authenticate($params);
if (is_soap_fault($start)) {
    trigger_error("SOAP Fault: (faultcode: {$start->faultcode}, faultstring: {$start->faultstring})", E_USER_ERROR);
    print "<br />";
} else {
    $response = $start->AuthenticateResult->Detail;
    $response_explode = explode(",",$response);
    $sessionid = $response_explode[1];
	$userid = 934;

    //Body of the Soap Header. 
    $headerbody = array('UserId' => $userid,'ClientId' => $clientid, 'SessionId' => $sessionid); 
    //Create Soap Header.        
    $header = new SOAPHeader($ns, 'UserSessionCredentials', $headerbody);       
    //set the Headers of Soap Client. 
    $client->__setSoapHeaders($header); 
	
	$body = array(  'TemplateName'=>$someTemplateName,
                'PageNo'=>$somePageNo,
                'ItemsPerPage'=>$someItemsPerPage,
                'SearchClause'=>$someSearchClause);

}
$params = array('getRequest' => $body);
$str = $client->GetData($params);

$new22 = $str->GetDataResult->Detail;
//$crit = str_replace('"', "", $new22);
//return $crit;
return $new22;
}

function download_csv_results($results, $name = NULL)
{
    if( ! $name)
    {
        $name = md5(uniqid() . microtime(TRUE) . mt_rand()). '.csv';
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename='. $name);
    header('Pragma: no-cache');
    header("Expires: 0");

echo $results;
}

//get a two dimensional array from the csv that a report "Detail" returns
function get2DimFromCSV($csv) {
	$lines = explode("\n", $csv); //explode by a new line, so you can get every line first

	$head = str_getcsv(array_shift($lines));

	$array = array();
	foreach ($lines as $line) {
		$row = array_pad(str_getcsv($line), count($head), '');
		$array[] = array_combine($head, $row);
	}
	return $array;
}

function integration_import($template,$csv,$action){
	$ns = "http://www.peoplevox.net/";
$clientid = "rng2744"; 
$username = "stats-api-mfc"; 
$password = base64_encode("rave");  
$socket_context = stream_context_create(array('http' => array('protocol_version'  => 1.0)));
$client = new SoapClient("http://wms.peoplevox.net/$clientid/resources/integrationservicev4.asmx?WSDL", array('exceptions' => 0,'stream_context' => $socket_context,'trace' => 1)); 
//$date = date("Y,m,d");

// body vars


$params = array("clientId"=>$clientid,"username"=>$username,"password"=>$password);
$start = $client->Authenticate($params);
if (is_soap_fault($start)) {
    trigger_error("SOAP Fault: (faultcode: {$start->faultcode}, faultstring: {$start->faultstring})", E_USER_ERROR);
    print "<br />";
} else {
    $response = $start->AuthenticateResult->Detail;
    $response_explode = explode(",",$response);
    $sessionid = $response_explode[1];
	$userid = 934;

    //Body of the Soap Header. 
    $headerbody = array('UserId' => $userid,'ClientId' => $clientid, 'SessionId' => $sessionid); 
    //Create Soap Header.        
    $header = new SOAPHeader($ns, 'UserSessionCredentials', $headerbody);       
    //set the Headers of Soap Client. 
    $client->__setSoapHeaders($header); 
	
	$body = array(
				'TemplateName'=>$template,
                'CsvData'=>$csv,
                'Action'=>$action
				);

}
$params = array('saveRequest' => $body);
$str = $client->SaveData($params);

//$new22 = $str->SaveDataResult->Detail;
$new22 = $str->SaveDataResult->Statuses->IntegrationStatusResponse->Status;
//$crit = str_replace('"', "", $new22);
//return $crit;
return $new22;
}
?>