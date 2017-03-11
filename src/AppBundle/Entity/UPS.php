<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UPS
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="UPS")
 */

class UPS {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $location;
    
    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $power;
    
    /**
     * @ORM\OneToMany(targetEntity="UPS_Status", mappedBy="UPS")
     */
    private $UPS_Status;
    
    /**
     * @ORM\ManyToMany(targetEntity="event", mappedBy="UPSs")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $events;
    
    public function __toString()
    {
        return (string) $this->getName();
    }
    
    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    /**
     * Get events
     *
     * @return \AppBundle\Entity\event
     */
    public function getEvents()
    {
        return $this->events;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set power
     *
     * @param string $power
     *
     * @return UPS
     */
    public function setPower($power)
    {
        $this->power = $power;

        return $this;
    }

    /**
     * Get power
     *
     * @return string
     */
    public function getPower()
    {
        return $this->power;
    }

    /**
     * Add uPSStatus
     *
     * @param \AppBundle\Entity\UPS_Status $uPSStatus
     *
     * @return UPS
     */
    public function addUPSStatus(\AppBundle\Entity\UPS_Status $uPSStatus)
    {
        $this->UPS_Status[] = $uPSStatus;

        return $this;
    }

    /**
     * Remove uPSStatus
     *
     * @param \AppBundle\Entity\UPS_Status $uPSStatus
     */
    public function removeUPSStatus(\AppBundle\Entity\UPS_Status $uPSStatus)
    {
        $this->UPS_Status->removeElement($uPSStatus);
    }

    /**
     * Get uPSStatus
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUPSStatus()
    {
        return $this->UPS_Status;
    }

    /**
     * Add events
     *
     * @param \AppBundle\Entity\event $event
     *
     * @return UPS
     */
    public function addEvents(\AppBundle\Entity\event $event)
    {
        $event->addUPSs($this);
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \AppBundle\Entity\event $event
     */
    public function removeEvents(\AppBundle\Entity\event $event)
    {
        $event->removeUPSs($this);
        $this->events->removeElement($event);
    }
}
