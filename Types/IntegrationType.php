<?php

namespace PVXArtisan\Types;

use PVXArtisan\Contracts\PVXTemplateTypeContract;

class IntegrationType implements PVXTemplateTypeContract{

    public static function getInstance() 
    {
        return new static;
    }

    public function column(String $value) : String 
    {
        return "$value";
    }

    public function equals(String $value) : String 
    {
        return " = \"$value\" ";
    }

    public function notEquals(String $value) : String 
    {
        return " != \"$value\" ";
    }

    public function contains(String $value) : String 
    {
        return ".Contains(\"$value\")";
    }

    public function notContains(String $value) : String 
    {

    }

    public function moreThan(String $value) : String
    {
        return "> \"$value\" ";
    }

    public function lessThan(String $value) : String
    {
        return "< \"$value\" ";
    }

    public function moreOrEquals(String $value) : String 
    {
        return ">= \"$value\" ";
    }

    public function lessOrEquals(String $value) : String
    {
        return "<= \"$value\" ";
    }

    public function startsWith(String $value) : String {
        return ".StartsWith(\"$value\")";
    }

    public function endsWith(String $value) : String {
        return ".EndsWith(\"$value\")";
    }

}