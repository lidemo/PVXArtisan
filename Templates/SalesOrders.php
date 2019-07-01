<?php

namespace PVXArtisan\Templates;

use PVXArtisan\Contracts\PVXTemplateContract;
use PVXArtisan\Types\IntegrationType;

class SalesOrders implements PVXTemplateContract{

    public $type = IntegrationType::class;

    public $columns = [
        'datetime'          => 'RequestedDeliveryDate',
        'order'             => 'SalesOrderNumber',
        'short order ref'   => 'CustomerPurchaseOrderReferenceNumber',
        'shipping adress 1' => 'ShippingAddressLine1',
        'shipping adress 2' => 'ShippingAddressLine2',
        'city'              => 'ShippingAddressCity',
        'region'            => 'ShippingAddressRegion',
        'postcode'          => 'ShippingAddressPostcode',
        'country'           => 'ShippingAddressCountry',
        'customer'          => 'Customer',
        'status'            => 'Status',
        'email'             => 'Status',
        'contact name'      => 'ContactName',
        'created'           => 'CreatedDate',
        'service type'      => 'ServiceType'
    ];

    public function getInstance() {
        return new self;
    }
}