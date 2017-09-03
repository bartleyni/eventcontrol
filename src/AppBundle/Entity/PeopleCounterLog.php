<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PeopleCounterLog
 *
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="people_counter_log")
 * @ORM\HasLifecycleCallbacks
 */

class PeopleCounterLog {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $timestamp;

    /**
     * @ORM\ManyToOne(targetEntity="event")
     * @ORM\JoinColumn(name="event", referencedColumnName="id")
     */
    protected $event;

    /**
     * @ORM\ManyToOne(targetEntity="venue")
     * @ORM\JoinColumn(name="venue", referencedColumnName="id")
     */
    protected $venue;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $running_in;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $running_out;
    
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
     * Set Timestamp
     *
     * @param \DateTime $timestamp
     *
     * @return PeopleCounterLog
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get Timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
    
    /**
     * Set event
     *
     * @param \AppBundle\Entity\event $event
     *
     * @return PeopleCounterLog
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

    /**
     * Get venue
     *
     * @return \AppBundle\Entity\venue
     */
    public function getVenue()
    {
        return $this->venue;
    }
    
    /**
     * Set venue
     *
     * @param \AppBundle\Entity\venue $venue
     *
     * @return PeopleCounterLog
     */
    public function setVenue(\AppBundle\Entity\venue $venue = null)
    {
        $this->venue = $venue;

        return $this;
    }
    
    /**
     * Set running_in
     *
     * @param integer $running_in
     *
     * @return PeopleCounterLog
     */
    public function setRunningIn($running_in)
    {
        $this->running_in = $running_in;

        return $this;
    }

    /**
     * Get running_in
     *
     * @return integer
     */
    public function getRunningIn()
    {
        return $this->running_in;
    }

    /**
     * Set running_out
     *
     * @param integer $running_out
     *
     * @return PeopleCounterLog
     */
    public function setRunningOut($running_out)
    {
        $this->running_out = $running_out;

        return $this;
    }

    /**
     * Get running_out
     *
     * @return integer
     */
    public function getRunningOut()
    {
        return $this->running_out;
    }

}
