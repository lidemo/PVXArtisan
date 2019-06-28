<?php

namespace PVXArtisan\Contracts;


interface PVXTemplateTypeContract{

    public static function getInstance();

    public function column(String $value) : String;
    public function equals(String $value) : String;
    public function notEquals(String $value) : String;
    public function moreThan(String $value) : String;
    public function lessThan(String $value) : String;
    public function moreOrEquals(String $value) : String;
    public function lessOrEquals(String $value) : String;
    public function contains(String $value) : String;
    public function notContains(String $value) : String;
    public function startsWith(String $value) : String;
    public function endsWith(String $value) : String;

}