<?php

namespace RoLocations\Countries;

use T4webBase\Domain\Entity;

class Countries extends Entity {

    protected $name;

    public function getName() {
        return $this->name;
    }

}
