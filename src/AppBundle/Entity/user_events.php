<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Description of user_events
 *
 * @author Nick
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_events")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\user_eventsRepository")
 */

class user_events {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="User_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $User_id;

    /**
     * @ORM\ManyToOne(targetEntity="event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $event_id;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $active;
    
    public function __toString()
    {
        return (string) $this->getName();
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
     * Get event_id
     *
     * @return integer
     */
    public function getEventId()
    {
        return $this->event_id;
    }
    
    /**
     * Set event_id
     *
     * @param \AppBundle\Entity\event $event
     *
     * @return user_events
     */
    public function setEventId(\AppBundle\Entity\event $event = null)
    {
        $this->event_id = $event;

        return $this;
    }

    /**
     * Get User_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->User_id;
    }
    
    /**
     * Set User_id
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return user_events
     */
    public function setUserId(\AppBundle\Entity\User $user = null)
    {
        $this->User_id = $user;

        return $this;
    }
    
    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return user_events
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
}
