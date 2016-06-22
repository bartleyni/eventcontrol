<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ControlRoomLED
 *
 * @author Nick
 */

/**
 * @ORM\Entity
 * @ORM\Table(name="ControlRoomLED")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ControlRoomLEDRepository")
 */

class ControlRoomLED {
    
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="ControlRoomLED")
     * @ORM\JoinColumn(name="User_id", referencedColumnName="id")
     */
    private $operator;
    
    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $ledRef;
    
    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $colour;
    
    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $brightness;
    
    
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
    
    
}
