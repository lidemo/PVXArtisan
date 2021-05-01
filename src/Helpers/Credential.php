<?php

namespace PVXArtisan\Helpers;

use PVXArtisan\Contracts\CredentialContract;

class Credential implements CredentialContract
{
    
    public static function getClientId()
    {
        return json_decode(file_get_contents(__DIR__.'/../Config/credentials.json'))->ClientId;
    }

    public static function getUsername()
    {
        return json_decode(file_get_contents(__DIR__.'/../Config/credentials.json'))->Username;
    }

    public static function getPassword()
    {
        return json_decode(file_get_contents(__DIR__.'/../Config/credentials.json'))->Password;
    }

}
