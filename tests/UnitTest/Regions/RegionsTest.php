<?php

namespace RoLocations\UnitTest\Regions;

use RoLocations\Regions\Regions;

class RegionsTest extends \PHPUnit_Framework_TestCase {
    
    public function testCanCreate() {
        $regions = new Regions();
        
        $this->assertInstanceOf('T4webBase\Domain\Entity', $regions);
        $this->assertObjectHasAttribute('id', $regions);
        $this->assertObjectHasAttribute('countryId', $regions);
        $this->assertObjectHasAttribute('name', $regions);
    }
}

