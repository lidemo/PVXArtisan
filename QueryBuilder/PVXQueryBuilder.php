<?php

namespace PVXArtisan\QueryBuilder;

use PVXArtisan\Exceptions\PVXQueryBuilderException;

use PVXArtisan\Contracts\PVXTemplateContract;
use PVXArtisan\Contracts\PVXTemplateTypeContract;


class PVXQueryBuilder{

    protected static $query = "";
    protected static $instance = null;

    protected static $template;
    protected static $templateType;

    function __construct(/* PVXTemplateContract $template */)  
    {
        //$this->template = $template;
    }

    /**
     * Static instances for easier daisy chaining
     */
    private static function getInstance()
    {
        if(empty(self::$instance) || self::$instance === null)
        {
            self::setInstance();
        }

        return self::$instance;
    }

    private static function setInstance()
    {
        self::$instance = new static();
    }

    //DATETIME METHODS MOVE MOST TO ANOTHER CLASS!
    public function datetime(String $from, String $to = null, String $datetimeColumn = null) : PVXQueryBuilder
    {
        $from = static::parseDateTime($from);

        if ($datetimeColumn == null) {
            if (static::$template == null ) {
                throw new PVXQueryBuilderException("
                    No template set for datetime() method. Use PVXQueryBuilder::linkTemplate() or supply datetime column as third parameter!
                ");
            }

            $datetimeColumn = self::$template->columns['datetime'];
        }

        
        static::$query .= self::$templateType->column($datetimeColumn);
        static::$query .= " >= DateTime($from)";

        if ($to !== null) { 
            $to = static::parseDateTime($to);
            static::$query .= " AND [$datetimeColumn] <= DateTime($to)";
        }

        return self::getInstance();
    }

    protected function parseDateTime(String $datetime) : String
    {
        $datetime = static::validateDatetime($datetime);

        $datetime = static::structureDatetime($datetime);

        return $datetime;
    }

    protected function structureDatetime(Array $datetime) : String 
    {
        $tempDay = $datetime[0];
        $datetime[0] = $datetime[2];
        $datetime[2] = $tempDay;

        return implode(",", $datetime);
    }

    protected function validateDatetime(String $datetime) : Array
    {
        $datetimeArr = preg_split("/([:\/ ])/", $datetime);

        $datetimeLength = count($datetimeArr);

        if ($datetimeLength > 6) throw new PVXQueryBuilderException("Too many elements for datetime");
        if (empty($datetimeArr[0])) throw new PVXQueryBuilderException("Too few elements for datetime");


        for ($i=0; $i < 6; $i++) {
            if ( $i == 1 && !isset($datetimeArr[$i]) ) {
                $datetimeArr[$i] = "06";  //GET THIS MONTH HERE FROM HELPER CLASS
            }else if ($i == 2 && !isset($datetimeArr[$i])) {
                $datetimeArr[$i] = "2019"; //GET THIS YEAR HERE
            }else if ( ($i == 3 || $i == 4 || $i == 5) && !isset($datetimeArr[$i]) ) {
                $datetimeArr[$i] = "00";
            }
        }

        return $datetimeArr;
        
    }

    

    //COND
    public function condition(String $column, String $operator, String $value) : PVXQueryBuilder
    {
        $tempQuery = '';
        $tempQuery .= self::$templateType->column(static::getColumn($column)); 
        //static::$query .= 

        //to add negative, append to a temp variable instead, then append to query

        $operator = \strtolower($operator);

        switch ($operator){
            case "=":
                $tempQuery .= self::$templateType->equals($value);
                break;
            case "!=":
                $tempQuery .= self::$templateType->equals($value);
                $tempQuery = "!" . $tempQuery;
                break;
            case ">":
                $tempQuery .= self::$templateType->moreThan($value);
                break;
            case "<":
                $tempQuery .= self::$templateType->lessThan($value);
                break;
            case ">=":
                $tempQuery .= self::$templateType->moreOrEquals($value);
                break;
            case "<=":
                $tempQuery .= self::$templateType->lessOrEquals($value);
                break;
            case "contains":
                $tempQuery .= self::$templateType->contains($value);
                break;
            case "not contains":
                $tempQuery .= self::$templateType->contains($value);
                $tempQuery = "!" . $tempQuery;
                break;
            case "startswith":
                $tempQuery .= self::$templateType->startsWith($value);
                break;
            case "endswith":
                $tempQuery .= self::$templateType->endsWith($value);
                break;
            default:
                throw new PVXQueryBuilderException("Wrong condition operator: $operator");
        }

        static::$query .= $tempQuery;

        return self::getInstance();
    }

    protected function getColumn(String $column) {
        if (static::$template == null) {
            return $column;
        }


        if (!isset( static::$template->columns[$column]) ) {
            return $column;
            //throw new PVXQueryBuilderException("Custom value $column not found in template".get_class(static::$template));
        }
        return static::$template->columns[$column];
    }

    public function and() : PVXQueryBuilder 
    {
        static::$query .= " AND ";

        return self::getInstance();
    }

    public function or() : PVXQueryBuilder 
    {
        static::$query .= " OR ";

        return self::getInstance();
    }

    public function openBracket() : PVXQueryBuilder 
    {
        static::$query .= "(";

        return self::getInstance();       
    }

    public function closeBracket() : PVXQueryBuilder 
    {
        static::$query .= ")";

        return self::getInstance();       
    }

    public static function linkTemplate(PVXTemplateContract $template) {
        self::$template = $template;
        self::$templateType = new $template->type();

        return self::getInstance();
    }

    public static function type(PVXTemplateTypeContract $type) {
        
        self::$templateType = $type;
        
        return self::getInstance();
    }

    public function getQuery() : String 
    {
        $query = static::$query;
        static::$query = "";
        static::$template = "";
        static::$templateType = "";

        return $query;
    }

}