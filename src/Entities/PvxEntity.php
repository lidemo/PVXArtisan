<?php

namespace PVXArtisan\Entities;

use PVXArtisan\Helpers\CsvToArrayConverter;
use PVXArtisan\PvxGetData;
use PVXArtisan\PvxGetReport;
use PVXArtisan\QueryBuilder\PVXQueryBuilder;
use PVXArtisan\Types\IntegrationType;
use PVXArtisan\Types\ReportType;

class PvxEntity
{
    protected $getReportObj;
    protected $pvxAuth;
    protected $template;
    protected $queryObj = "";
    protected $queryConjunction = "";

    public function __construct($pvxAuth, $template)
    {
        $this->pvxAuth = $pvxAuth;
        $this->template = $template;
        $this->getReportObj = new PvxGetData($pvxAuth->authenticate());
        
        $this->getReportObj->setTemplateName($this->template);
    }

    public function whereDateBetween($columnName, $datetimeFrom, $datetimeTo = null)
    {

        if ($this->queryObj == "") {
            $this->queryObj = PVXQueryBuilder::type(new IntegrationType());
        }
        
        $this->queryObj = $this->queryObj->and()->datetime($datetimeFrom, $datetimeTo, $columnName);

        return $this;
    }

    public function where($column, $comparison, $value = null)
    {
        if ($this->queryObj == "") {
            $this->queryObj = PVXQueryBuilder::type(new IntegrationType());
        }

        if (is_callable($column)) {
            $this->queryObj->and();
            $this->queryObj->openBracket();
            $column($this);
            $this->queryObj->closeBracket();
            return $this;
        }

        $this->queryConjunction = 'AND';
        if ($value == null) {
            $value = $comparison;
            $comparison = '=';
        }
        
        $this->queryObj = $this->queryObj->and()->condition($column,$comparison,$value);

        return $this;
    }

    public function orWhere($column, $comparison, $value = null)
    {
        if ($this->queryObj == "") {
            $this->queryObj = PVXQueryBuilder::type(new IntegrationType());
        }

        if (is_callable($column)) {
            $this->queryObj->or();
            $this->queryObj->openBracket();
            $column($this);
            $this->queryObj->closeBracket();
            return $this;
        }

        $this->queryConjunction = 'OR';
        if ($value == null) {
            $value = $comparison;
            $comparison = '=';
        }
        
        $this->queryObj = $this->queryObj->or()->condition($column,$comparison,$value);

        return $this;   
    }

    public function first($columns = null)
    {
        if ($columns != null) $this->setColumns($columns);
        if ($this->queryObj != "") $this->getReportObj->setSearchClause($this->createQuery());
        
        $data = CsvToArrayConverter::csvToTwoDimensional( $this->getReportObj->get(), 'pop');
        return json_decode(json_encode($data[0]), FALSE);
    }

    public function get($columns = null)
    {
        if ($columns != null) $this->setColumns($columns);
        if ($this->queryObj != "") $this->getReportObj->setSearchClause($this->createQuery());

        $data = CsvToArrayConverter::csvToTwoDimensional( $this->getReportObj->get(), 'pop');

        $objectArray = [];
        foreach($data as $itemData) {
            $objectArray[] = json_decode(json_encode($itemData), FALSE);
        }

        return $objectArray;
    }

    public function createQuery()
    {
        /** Remove last AND */
        $query = trim($this->queryObj->getQuery());
        
        if (substr($query, 0, strlen('AND')) == 'AND') {
            $query = substr($query, strlen('AND'));
        }else if (substr($query, 0, strlen('OR')) == 'OR') {
            $query = substr($query, strlen('OR'));
        }

        $query = $this->clearConjuctions($query);
        
        return $query;
    }

    public function clearConjuctions($query)
    {
        $query = str_replace('( AND', '(', $query);
        $query = str_replace('( OR', '(', $query);

        return $query;
    }
    
    public function setColumns(Array $columns)
    {
        $fColumns = '';
        foreach ($columns as $key => $column) {
            $fColumns .= "[$column]";
            if ($key+1 < count($columns)) $fColumns .= ',';
        }

        $this->getReportObj->setColumns($fColumns);
        
    }
}
