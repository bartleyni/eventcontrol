<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of camera
 *
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
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
     * @ORM\ManyToOne(targetEntity="event", inversedBy="people_counter_log")
     * @ORM\JoinColumn(name="event", referencedColumnName="id")
     */
    protected $event;

    /**
     * @ORM\ManyToOne(targetEntity="venue", inversedBy="people_counter_log")
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

}
