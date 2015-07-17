<?php

namespace RoLocations\Cities;

use T4webBase\Domain\Entity;

class Cities extends Entity {

    protected $regionId;
    protected $countryId;
    protected $name;

    public function getRegionId() {
        return $this->regionId;
    }

    public function getCountryId() {
        return $this->countryId;
    }

    public function getName() {
        return $this->name;
    }

}
