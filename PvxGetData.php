<?php

namespace PVXArtisan;

use SOAPHeader;

class PvxGetData
{
    protected $pvxAuth;
    protected $templateName = "";
    protected $pageNo = "1";
    protected $itemsPerPage = "0";
    protected $searchClause = "";

    protected $responseId;
    protected $totalCount;
    protected $detail;
    protected $errorCode;


    public function __construct(PvxApiAuth $pvxAuth) {
        $this->pvxAuth = $pvxAuth;
    }

    public function get() {
        if ($this->templateName == "") {
            return "No template name specified";
        }

        //Create Soap Header
		$headerbody = [
            'UserId' => 0,
            'ClientId' => $this->pvxAuth->getClientId(), 
            'SessionId' => $this->pvxAuth->getSessionId()
        ];

		$header = new SOAPHeader("http://www.peoplevox.net/", 'UserSessionCredentials', $headerbody);
        $this->pvxAuth->getClient()->__setSoapHeaders($header);
        
        $soapBody = [ 
            'getRequest' => [
                'TemplateName' => $this->templateName,
                'PageNo'       => $this->pageNo,
                'ItemsPerPage' => $this->itemsPerPage,
                'SearchClause' => $this->searchClause
            ] 
        ];

        $result = $this->pvxAuth->getClient()->GetData($soapBody);

        if (is_soap_fault($result)) {
            $this->printError($result);
        }

        $this->saveReturnedData($result->GetDataResult);
        return $result->GetDataResult->Detail;
        
    } 
    
    protected function printError($apiMethodResponse) 
    {
        trigger_error("SOAP PVX Auth Error: $apiMethodResponse->faultcode; $apiMethodResponse->faultstring", E_USER_ERROR);
        echo '<br />';
    }

    protected function saveReturnedData($data) {
        $this->responseId = $data->ResponseId;
        $this->totalCount = $data->TotalCount;
        $this->detail = $data->Detail;
        $this->errorCode = $data->ErrorCode;
    }

    public function getResponseId() {
        return $this->responseId;
    }

    public function getTotalCount() {
        return $this->totalCount;
    }

    public function getDetail() {
        return $this->detail;
    }

    public function getErrorCode() {
        return $this->errorCode;
    }

    public function setTemplateName(String $templateName) {
        $this->templateName = $templateName;
    }

    public function setPageNo(Int $pageNo) {
        $this->pageNo = $pageNo;
    }

    public function setItemsPerPage(Int $itemsPerPage) {
        $this->itemsPerPage = $itemsPerPage;
    }

    public function setSearchClause(String $searchClause) {
        $this->searchClause = $searchClause;
    }

}