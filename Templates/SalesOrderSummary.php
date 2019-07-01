<?php

class SalesOrderSummary{

    public $type = ReportType::class;

    public $columns = [
        'datetime'  => 'Requested delivery date',
        'order'   => 'Sales order no.',
        
    ];

    public function getInstance() {
        return new self;
    }
}