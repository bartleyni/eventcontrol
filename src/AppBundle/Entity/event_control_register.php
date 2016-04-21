<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of log_operator
 *
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="event_control_register")
 */

class event_control_register {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
 
    /**
     * @ORM\Column(type="string", length=60)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $phone;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $email;
   
    /**
     * @ORM\Column(type="datetime")
     */
    protected $time_in;
    
     /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $time_out;
    
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
     * Set name
     *
     * @param string $name
     *
     * @return event_control_register
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return event_control_register
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return event_control_register
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set timeIn
     *
     * @param \DateTime $timeIn
     *
     * @return event_control_register
     */
    public function setTimeIn($timeIn)
    {
        $this->time_in = $timeIn;

        return $this;
    }

    /**
     * Get timeIn
     *
     * @return \DateTime
     */
    public function getTimeIn()
    {
        return $this->time_in;
    }

    /**
     * Set timeOut
     *
     * @param \DateTime $timeOut
     *
     * @return event_control_register
     */
    public function setTimeOut($timeOut)
    {
        $this->time_out = $timeOut;

        return $this;
    }

    /**
     * Get timeOut
     *
     * @return \DateTime
     */
    public function getTimeOut()
    {
        return $this->time_out;
    }
}
