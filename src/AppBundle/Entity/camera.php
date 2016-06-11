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
 * @author Matthew
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="camera")
 * @ORM\HasLifecycleCallbacks
 */

class camera {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $camera_id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $timestamp;

    /**
     * @ORM\Column(type="integer")
     */
    protected $count;

    /**
     * @ORM\Column(type="integer")
     */
    protected $running_count;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $doors;

    /**
     * @ORM\ManyToOne(targetEntity="venue")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id")
     */
    protected $venue;





    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @ORM\PrePersist
     * @return Example
     */
    public function setTimestamp()
    {

        if(!$this->timestamp){
            $this->timestamp = new \DateTime();
        }

        return $this;
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
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return camera
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set runningCount
     *
     * @param integer $runningCount
     *
     * @return camera
     */
    public function setRunningCount($runningCount)
    {
        $this->running_count = $runningCount;

        return $this;
    }

    /**
     * Get runningCount
     *
     * @return integer
     */
    public function getRunningCount()
    {
        return $this->running_count;
    }

    /**
     * Set doors
     *
     * @param boolean $doors
     *
     * @return camera
     */
    public function setDoors($doors)
    {
        $this->doors = $doors;

        return $this;
    }

    /**
     * Get doors
     *
     * @return boolean
     */
    public function getDoors()
    {
        return $this->doors;
    }

    /**
     * Set venue
     *
     * @param \AppBundle\Entity\venue $venue
     *
     * @return camera
     */
    public function setVenue(\AppBundle\Entity\venue $venue = null)
    {
        $this->venue = $venue;

        return $this;
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

}



