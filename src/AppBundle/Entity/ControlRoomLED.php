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
 * @ORM\Table(name="ControlRoomLED", indexes={@ORM\Index(name="timestamp_led_idx", columns={"timestamp", "led_ref"})}))
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
     * @ORM\ManyToOne(targetEntity="User")
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
     * @return ControlRoomLED
     */
    public function setTimestamp()
    {

        if(!$this->timestamp){
            $this->timestamp = new \DateTime();
        }

        return $this;
    }
    
    /**
     * Set operator
     *
     * @param \AppBundle\Entity\User $operator
     *
     * @return ControlRoomLED
     */
    public function setOperator(\AppBundle\Entity\User $operator = null)
    {
        $this->operator = $operator;

        return $this;
    }
    
    /**
     * Set ledRef
     * @param string $ledRef
     * @return ControlRoomLED
     */
    public function setledRef($ledRef)
    {
        $this->ledRef = $ledRef;

        return $this;
    }
    
    /**
     * Set colour
     * @param string $colour
     * @return ControlRoomLED
     */
    public function setColour($colour)
    {
        $this->colour = $colour;

        return $this;
    }
    
    /**
     * Set brightness
     * @param string $brightness
     * @return ControlRoomLED
     */
    public function setBrightness($brightness)
    {
        $this->brightness = $brightness;

        return $this;
    }
}
