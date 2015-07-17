<?php

namespace RoLocations\UnitTest\Cities;

use RoLocations\Cities\Cities;

class CitiesTest extends \PHPUnit_Framework_TestCase {
    
    public function testCanCreate() {
        $cities = new Cities();
        
        $this->assertInstanceOf('T4webBase\Domain\Entity', $cities);
        $this->assertObjectHasAttribute('id', $cities);
        $this->assertObjectHasAttribute('regionId', $cities);
        $this->assertObjectHasAttribute('countryId', $cities);
        $this->assertObjectHasAttribute('name', $cities);
    }
}

