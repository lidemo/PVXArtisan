<?php

namespace PVXArtisan;

use SOAPHeader;

class PvxGetReport{
    protected $pvxAuth;
    protected $templateName = "";
    protected $pageNo = "1";
    protected $itemsPerPage = "0";
    protected $searchClause = "";
    protected $columns = "";
    protected $orderBy = "";

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
            'getReportRequest' => [
                'TemplateName' => $this->templateName,
                'PageNo'       => $this->pageNo,
                'ItemsPerPage' => $this->itemsPerPage,
                'SearchClause' => $this->searchClause,
                'Columns'      => $this->columns,
                'OrderBy'       => $this->orderBy,
            ] 
        ];

        $result = $this->pvxAuth->getClient()->GetReportData($soapBody);

        if (is_soap_fault($result)) {
            $this->printError($result);
        }

        $this->saveReturnedData($result->GetReportDataResult);
        return $result->GetReportDataResult->Detail;
        
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

    public function setColumns(String $columns) {
        $this->columns = $columns;
    }

    public function orderBy(String $orderBy)
    {
        $this->orderBy = $orderBy;
    }


}