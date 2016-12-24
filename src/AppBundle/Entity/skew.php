<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of venue_camera
 *
 *
 * @author Matthew
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="skew")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\skewRepository")
 * @ORM\HasLifecycleCallbacks
 */


class skew {

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
     * @ORM\Column(type="integer")
     */
    protected $skew_in;

    /**
     * @ORM\Column(type="integer")
     */
    protected $skew_out;

    /**
     * @ORM\ManyToOne(targetEntity="venue", inversedBy="skew")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id")
     */
    protected $venue_id;


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
     * Set skewIn
     *
     * @param integer $skewIn
     *
     * @return skew
     */
    public function setSkewIn($skewIn)
    {
        $this->skew_in = $skewIn;

        return $this;
    }

    /**
     * Get skewIn
     *
     * @return integer
     */
    public function getSkewIn()
    {
        return $this->skew_in;
    }

    /**
     * Set skewOut
     *
     * @param integer $skewOut
     *
     * @return skew
     */
    public function setSkewOut($skewOut)
    {
        $this->skew_out = $skewOut;

        return $this;
    }

    /**
     * Get skewOut
     *
     * @return integer
     */
    public function getSkewOut()
    {
        return $this->skew_out;
    }

    /**
     * Set venueId
     *
     * @param \AppBundle\Entity\venue $venueId
     *
     * @return skew
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
