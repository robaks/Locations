<?php

namespace RoLocations\UnitTest\Countries;

use RoLocations\Countries\Countries;

class CountriesTest extends \PHPUnit_Framework_TestCase {
    
    public function testCanCreate() {
        $countries = new Countries();
        
        $this->assertInstanceOf('T4webBase\Domain\Entity', $countries);
        $this->assertObjectHasAttribute('id', $countries);
        $this->assertObjectHasAttribute('name', $countries);
    }
}

