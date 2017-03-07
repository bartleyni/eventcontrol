<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of venue_event
 *
 *
 * @author Matthew
 */
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table(name="venue_event")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\venue_eventRepository")
 */
class venue_event {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**		 
    *		
    * @ORM\ManyToOne(targetEntity="event", inversedBy="venue_event")		
    * @ORM\JoinColumn(name="event_id", referencedColumnName="id")		
    *		
    */		
    protected $event_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="venue", inversedBy="venue_event")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id")
     */
    protected $venue_id;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $doors;


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
     * Set inverse
     *
     * @param boolean $inverse
     *
     * @return venue_camera
     */
    public function setDoors($doors)
    {
        $this->doors = $doors;

        return $this;
    }

    /**
     * Get inverse
     *
     * @return boolean
     */
    public function getDoors()
    {
        return $this->doors;
    }

    /**
     * Set cameraId
     *
     * @param \AppBundle\Entity\camera $cameraId
     *
     * @return venue_camera
     */
    public function setEventId(\AppBundle\Entity\event $eventId = null)
    {
        $this->event_id = $eventId;

        return $this;
    }

    /**
     * Get cameraId
     *
     * @return \AppBundle\Entity\camera
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * Set venueId
     *
     * @param \AppBundle\Entity\venue $venueId
     *
     * @return venue_camera
     */
    public function setVenueId(\AppBundle\Entity\venue $venueId = null)
    {
        $this->venue_id = $venueId;

        return $this;
    }

    /**
     * Get venueId
     *
     * @return \AppBundle\Entity\venue
     */
    public function getVenueId()
    {
        return $this->venue_id;
    }
}
