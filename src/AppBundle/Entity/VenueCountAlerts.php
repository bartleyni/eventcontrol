<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of VenueCountAlerts
 *
 * @author Nick
 */

/**
 * @ORM\Entity
 * @ORM\Table(name="VenueCountAlerts")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\locationsRepository")
 */
class VenueCountAlerts {
    
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank
     * @Assert\Length(min = 3)
     */
    private $description;
    
    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank
     * @Assert\Length(min = 2)
     */
    private $upDownBoth;
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $count;
    
    /**
     * @ORM\ManyToOne(targetEntity="venue_event", inversedBy="countAlerts")
     * @ORM\JoinColumn(name="venue_event", referencedColumnName="id", nullable=false)
     */
    private $venueEvent;
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setDescription($text)
    {
        $this->description = $text;
    }
    
    public function getUpDownBoth()
    {
        return $this->upDownBoth;
    }
    
    public function setUpDownBoth($upDownBoth)
    {
        $this->upDownBoth = $upDownBoth;
    }
 
    public function getCount()
    {
        return $this->count;
    }
    
    public function setCount($count)
    {
        $this->count = $count;
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
     * Get venue_event
     *
     * @return \AppBundle\Entity\venue_event
     */
    public function getVenueEvent()
    {
        return $this->venueEvent;
    }
    
    public function addVenueEvent(venue_event $venue_event)
    {
        if (!$this->venueEvent->contains($venue_event)) {
            $this->venueEvent->add($venue_event);
        }
    }
}
