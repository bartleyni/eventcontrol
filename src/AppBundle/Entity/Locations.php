<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Locations
 *
 * @author Nick
 */
class Locations {
    
    private $locationText;
    
    private $locationLatLong;
    
    public function getLocationText()
    {
        return $this->locationText;
    }
    
    public function setLocationText($text)
    {
        $this->locationText = $text;
    }
    
    public function getLocationLatLong()
    {
        return $this->locationLatLong;
    }
    
    public function setLocationLatLong($latLong)
    {
        $this->locationLatLong = $latLong;
    }
    
}