<?php

namespace PVXArtisan\QueryBuilder;

class PVXQueryBuilder{

    protected $resultQuery;

    function __construct() {

    }

    public function buildQuery(Array $queryRules):String {

        foreach ($queryRules as $column => $rule) {

            $this->parseRule($column, $rule);

        }

    }

    protected function parseRule(String $column, $rule) {

        if (is_array($rule)) {
            foreach ($rule as $operation => $sub) {
                
                $resultQuery .= "[$column]$operation ";
            }
        }

    }

}