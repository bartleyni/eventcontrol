<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Description of UPS_Status
 *
 * @author Nick
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="UPS_Status")
 */
class UPS_Status {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="UPS", inversedBy="UPS_Status")
     * @ORM\JoinColumn(name="UPS_id", referencedColumnName="id")
     */
    private $UPS;
    
    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $status;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $timestamp;
    
    public function __toString()
    {
        return (string) $this->getName();
    }
    
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
     * Set UPS
     *
     * @param \AppBundle\Entity\UPS $ups
     *
     * @return UPS_Status
     */
    public function setUPS(\AppBundle\Entity\UPS $ups = null)
    {
        $this->UPS = $ups;

        return $this;
    }

    /**
     * Get UPS
     *
     * @return \AppBundle\Entity\UPS
     */
    public function getUPS()
    {
        return $this->UPS;
    }
    
    /**
     * Set status
     *
     * @param string $status
     *
     * @return UPS_Status
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        $timeNow = new \DateTime();
        
        if (($timeNow - $this->timestamp) > 10)
        {
            return "Timeout";
        }
        else
        {
            return $this->status;
        }
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
}
