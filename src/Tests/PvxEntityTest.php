<?php

namespace PVXArtisan\Tests;

use PVXArtisan\Helpers\Credential;
use PVXArtisan\Entities\PvxEntity;
use PHPUnit\Framework\TestCase;
use PVXArtisan\PvxApiAuth;

class PvxEntityTest extends TestCase
{

    public function authenticate()
    {
        return new PvxApiAuth(Credential::getClientId(), Credential::getUsername(), Credential::getPassword());
    }

    public function test_pvx_entity_single_row()
    {
        $pvxAuth = $this->authenticate();
        $data = (new PvxEntity($pvxAuth, 'Sales orders'))->where('SalesOrderNumber', 'W2794786-1D-V')->first();


        $this->assertIsObject($data);
        $this->assertObjectHasAttribute('Status', $data);
    }

    public function test_pvx_entity_multiple_rows()
    {
        $pvxAuth = $this->authenticate();
        $data = (new PvxEntity($pvxAuth, 'Sales orders'))->where('SalesOrderNumber','EndsWith','-N')->get();

        $this->assertArrayHasKey('RequestedDeliveryDate',$data);
    }

}
