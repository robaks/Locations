<?php

namespace RoLocations\Regions;

use T4webBase\Domain\Entity;

class Regions extends Entity {

    protected $countryId;
    protected $name;

    public function getCountryId() {
        return $this->countryId;
    }

    public function getName() {
        return $this->name;
    }

}
