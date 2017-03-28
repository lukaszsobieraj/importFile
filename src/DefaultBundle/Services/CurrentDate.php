<?php

namespace DefaultBundle\Services;

class CurrentDate {

    private $dateNow;

    public function getDate() {

        $this->dateNow = new \DateTime();
        return $this->dateNow->format('d-m-Y');
        
    }

}
