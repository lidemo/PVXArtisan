<?php

namespace PVXArtisan;

class PvxImportData{

    protected $pvxAuth;
    protected $templateName;

    function __construct(PvxApiAuth $pvxAuth) {
        $this->pvxAuth = $pvxAuth;
    }

    public function saveData($templateName, $csvData, $action) {
        $headerbody = [
            'UserId' => 0,
            'ClientId' => $this->pvxAuth->getClientId(), 
            'SessionId' => $this->pvxAuth->getSessionId()
        ];

		$header = new \SOAPHeader("http://www.peoplevox.net/", 'UserSessionCredentials', $headerbody);
        $this->pvxAuth->getClient()->__setSoapHeaders($header);

        $soapBody = [
            'saveRequest'   => [
                'TemplateName'  => $templateName,
                'CsvData'       => $csvData,
                'Action'        => $action
            ]
        ];



        $result = $this->pvxAuth->getClient()->GetSaveTemplate();
        $template = $result->SaveData;

        var_dump($template);

        if (is_soap_fault($result)) {
            throw new Exception($result);
        }

    }

    public function getSaveTemplate($templateName) {
        $headerbody = [
            'UserId' => 0,
            'ClientId' => $this->pvxAuth->getClientId(), 
            'SessionId' => $this->pvxAuth->getSessionId()
        ];

		$header = new \SOAPHeader("http://www.peoplevox.net/", 'UserSessionCredentials', $headerbody);
        $this->pvxAuth->getClient()->__setSoapHeaders($header);

        $soapBody = [
                'templateName'  => $templateName
            ];



        $template = $this->pvxAuth->getClient()->GetSaveTemplate($soapBody);
        
        //echo 'ddsa' . htmlentities();

        $xml = $this->pvxAuth->getClient()->__getLastRequest();
        $sXML = new \SimpleXMLElement($xml);
        echo htmlentities($sXML->asXML());

        var_dump($template->GetSaveTemplateResult);

        if (is_soap_fault($template)) {
            throw new Exception($template);
        }

    }

}