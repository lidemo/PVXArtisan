<?php

namespace PVXArtisan;

use SoapClient;

class PvxApiAuth
{

    private $client;
    private $clientId;
    private $username;

    private $sessionId;

    public function __construct(String $clientId, String $username, String $password, String $env = 'wms') 
    {

        $this->clientId = $clientId;
        $this->username = $username;
        $this->password = base64_encode($password);

        $this->client = new SoapClient("http://$env.peoplevox.net/".$this->clientId."/resources/integrationservicev4.asmx?WSDL", ["trace" => 1]);

    }

    public function authenticate() 
    {
        $params = [
            'clientId'  => $this->clientId,
            'username'  => $this->username,
            'password'  => $this->password
        ];

        $authentication = $this->client->Authenticate($params);

        if (is_soap_fault($authentication)) {
            $this->printError($authentication);
        }else {
            $this->sessionId = explode(',', $authentication->AuthenticateResult->Detail);
            $this->sessionId = $this->sessionId[1];
            
            return $this;
        }
    }

    protected function printError($apiMethodResponse) 
    {
        trigger_error("SOAP PVX Auth Error: $apiMethodResponse->faultcode; $apiMethodResponse->faultstring", E_USER_ERROR);
        echo '<br />';
    }

    public function getClient() 
    {
        return $this->client;
    }

    public function getSessionId() 
    {
        return $this->sessionId;
    }

    public function getClientId() 
    {
        return $this->clientId;
    }


}


/* require_once 'PvxGetData.php';
require_once 'func.php';

$pvxAuthObj = new PvxApiAuth('client_id', 'username', 'password');
$authObj = $pvxAuthObj->authenticate();

$getDataObj = new PvxGetData($authObj);
$getDataObj->setTemplateName('Item types');
$getDataObj->setItemsPerPage(10);
$data = $getDataObj->get();

echo $getDataObj->getTotalCount();

$data = get2DimFromCSV($data);
echo '<pre>';
print_r($data);
echo '</pre>'; */

//$authObj->
