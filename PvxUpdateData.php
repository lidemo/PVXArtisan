<?php

namespace PVXArtisan;

use PVXArtisan\Helpers\CsvToArrayConverter;
use PVXArtisan\Exceptions\PVXImportDataException;
use PVXArtisan\Exceptions\PVXUpdateDataException;

class PvxUpdateData{

    protected $pvxAuth;
    protected $templateName;

    protected $fieldsCollection;
    protected $whereClauses = '';
    protected $importCsv;

    function __construct(PvxApiAuth $pvxAuth, String $templateName) 
    {
        $this->pvxAuth = $pvxAuth;
        $this->templateName = $templateName;

        $this->fieldsCollection = new \stdClass();
        
        
    }

    public function update(int $action = 0) 
    {
        if (!isset($this->query)) {
            throw new PVXUpdateDataException("No query set. Use \$pvxUpdateData->query(query)\" to set a query.");
        }

        $this->checkItemExists();
        $this->buildCsv();
        
        
        $headerbody = [
            'UserId' => 0,
            'ClientId' => $this->pvxAuth->getClientId(), 
            'SessionId' => $this->pvxAuth->getSessionId()
        ];

		$header = new \SOAPHeader("http://www.peoplevox.net/", 'UserSessionCredentials', $headerbody);
        $this->pvxAuth->getClient()->__setSoapHeaders($header);

        $soapBody = [
            'saveRequest'   => [
                'TemplateName'  => $this->templateName,
                'CsvData'       => $this->importCsv,
                'Action'        => $action
            ]
        ];

        $result = $this->pvxAuth->getClient()->SaveData($soapBody);

        if (is_soap_fault($result)) {
            throw new PVXUpdateDataException($result);
        }

        if ($result->SaveDataResult->ResponseId == -1) {
            throw new PVXUpdateDataException("Update data failed with error msg: {$result->SaveDataResult->Detail}");
        }

    }

    public function set(String $column, String $value) 
    {
        if (!\in_array($column, $this->templateHeaders)) {
            throw new PVXImportDataException("There is no $column column in the template you are attempting to import to");
        }
        $this->fieldsCollection->{$column} = $value;

        return $this;
    }


    public function query(String $query) 
    {
        $this->query = $query;

        return $this;
    }

    public function where(String $where) 
    {
        //FIX or remove
        $this->whereClauses = $where;
    }

    public function buildCsv() 
    {
        $columns = '';
        $values = '';

        $counter = 1;
        foreach ($this->fieldsCollection as $column => $value) {
            $columns .= $column . ($counter < count(get_object_vars($this->fieldsCollection)) ? ',' : PHP_EOL);
            $values .= $value . ($counter < count(get_object_vars($this->fieldsCollection)) ? ',' : PHP_EOL);
            $counter++;
        }
        
        $this->importCsv = $columns . $values;
    }

    public function getTemplateHeaders() 
    {
        $pvxGetSaveTemplate = new PvxGetSaveTemplate($this->pvxAuth);
        $templateCsv = $pvxGetSaveTemplate->get($this->templateName);

        if ($pvxGetSaveTemplate->getResponse()->TotalCount < 0) {
            throw new PVXUpdateDataException("No template with $this->templateName found.");
        }

        $this->templateHeaders = explode(',', $templateCsv);

        return true;
    }

    public function checkItemExists() 
    {
        $pvxGetData = new PvxGetData($this->pvxAuth);
        $pvxGetData->setTemplateName($this->templateName);
        $pvxGetData->setSearchClause($this->query);

        $data = $pvxGetData->get();

        $resultCount = $pvxGetData->getTotalCount();

        if ($resultCount < 1) {
            throw new PVXUpdateDataException("No record in PVX found conforming to this query: $this->query.");
        }

        return true;
    }

}