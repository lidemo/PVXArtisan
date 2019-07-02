<?php

namespace PVXArtisan;

class PvxGetSaveTemplate{

    protected $pvxAuth;

    function __construct(PvxApiAuth $pvxAuth) {
        $this->pvxAuth = $pvxAuth;
    }

    public function get(String $templateName) {
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


        if (is_soap_fault($template)) {
            throw new Exception($template);
        }

        return $template->GetSaveTemplateResult->Detail;
    }

}