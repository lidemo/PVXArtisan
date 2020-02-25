<?php

namespace PVXArtisan;

use PVXArtisan\Exceptions\PVXGetSaveTemplateException;

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

        //possibly save last request for debbuging? also if in all classes might see if
        //inheritance good idea
/*         $xml = $this->pvxAuth->getClient()->__getLastRequest();
        $sXML = new \SimpleXMLElement($xml);
        echo htmlentities($sXML->asXML()); */


        if (is_soap_fault($template)) {
            throw new Exception($template);
        }

        $this->response =  $template->GetSaveTemplateResult;
        return $template->GetSaveTemplateResult->Detail;
    }

    public function getResponse() {
        if (!isset($this->response)) {
            throw new PVXGetSaveTemplateException('No response has been received yet.');
        }
        return $this->response;
    }

}