<?php

namespace PVXArtisan;

use PVXArtisan\Exceptions\PVXImportDataException;

class PvxImportData{

    protected $pvxAuth;
    protected $templateName;

    protected $fieldsCollection;
    protected $importCsv;

    function __construct(PvxApiAuth $pvxAuth, String $templateName) 
    {
        $this->pvxAuth = $pvxAuth;
        $this->templateName = $templateName;

        $this->fieldsCollection = new \stdClass();
        $this->getTemplateHeaders($templateName);
        
    }

    public function save(int $action = 0) 
    {
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

        echo '<pre>';
        print_r($this->pvxAuth->getClient());
        $result = $this->pvxAuth->getClient()->SaveData($soapBody);

        if (is_soap_fault($result)) {
            throw new PVXImportDataException($result);
        }

        if ($result->SaveDataResult->ResponseId == -1) {
            throw new PVXImportDataException("Save data failed with error msg: {$result->SaveDataResult->Detail}");
        }

    }

    public function add(String $column, $value) 
    {
        if (!\in_array($column, $this->templateHeaders)) {
            throw new PVXImportDataException("There is no $column column in the template you are attempting to import to");
        }
        $this->fieldsCollection->{$column} = $value;

        return $this;
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
        echo '<br />CSV:' . $this->importCsv;
    }

    public function getCsv() 
    {
         
    }

    public function getTemplateHeaders($templateName) 
    {
        $pvxGetSaveTemplate = new PvxGetSaveTemplate($this->pvxAuth);
        $templateCsv = $pvxGetSaveTemplate->get($templateName);

        var_dump($templateCsv);

        $this->templateHeaders = explode(',', $templateCsv);
    }

}