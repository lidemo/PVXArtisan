<?php

namespace PVXArtisan\Templates;

use PVXArtisan\Contracts\PVXTemplateContract;
use PVXArtisan\Types\ReportType;

class ItemMovementHistory implements PVXTemplateContract{

    public $type = ReportType::class;

    public $columns = [
        'datetime'  => 'Date timestamp',
        'order'   => 'Sales order number',
        'user'    => 'User',
        'from'    => 'From',
        'to'      => 'To'
    ];

    public function getInstance() {
        return new self;
    }

}