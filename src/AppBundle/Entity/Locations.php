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

/**
 * @ORM\Entity
 * @ORM\Table(name="Locations")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\locationsRepository")
 */
class Locations {
    
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=200)
     */
    private $locationText;
    
    /**
     * @ORM\Column(type="string", length=200)
     */
    private $locationLatLong;
    
    /**
     * @ORM\ManyToOne(targetEntity="event", inversedBy="locations")
     * @ORM\JoinColumn(name="event", referencedColumnName="id", nullable=false)
     */
    private $event;
    
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
    

    /**
     * Get id
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set event
     *
     * @param \AppBundle\Entity\event $event
     *
     * @return Locations
     */
    public function setEvent(\AppBundle\Entity\event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \AppBundle\Entity\event
     */
    public function getEvent()
    {
        return $this->event;
    }
    
    public function addEvent(event $event)
    {
        if (!$this->event->contains($event)) {
            $this->event->add($event);
        }
    }
}
