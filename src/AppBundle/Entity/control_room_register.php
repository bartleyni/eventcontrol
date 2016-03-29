<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of control_room_register
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="control_room_register")
 */

class control_room_register {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $sign_in_timestamp;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $sign_out_timestamp;
    
    /**
     * @ORM\ManyToOne(targetEntity="log_operator", inversedBy="control_room_register")
     * @ORM\JoinColumn(name="log_operator_id", referencedColumnName="id")
     */
    private $operator;

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
     * Set signInTimestamp
     *
     * @param \DateTime $signInTimestamp
     *
     * @return control_room_register
     */
    public function setSignInTimestamp($signInTimestamp)
    {
        $this->sign_in_timestamp = $signInTimestamp;

        return $this;
    }

    /**
     * Get signInTimestamp
     *
     * @return \DateTime
     */
    public function getSignInTimestamp()
    {
        return $this->sign_in_timestamp;
    }

    /**
     * Set signOutTimestamp
     *
     * @param \DateTime $signOutTimestamp
     *
     * @return control_room_register
     */
    public function setSignOutTimestamp($signOutTimestamp)
    {
        $this->sign_out_timestamp = $signOutTimestamp;

        return $this;
    }

    /**
     * Get signOutTimestamp
     *
     * @return \DateTime
     */
    public function getSignOutTimestamp()
    {
        return $this->sign_out_timestamp;
    }

    /**
     * Set operator
     *
     * @param \AppBundle\Entity\log_operator $operator
     *
     * @return control_room_register
     */
    public function setOperator(\AppBundle\Entity\log_operator $operator = null)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return \AppBundle\Entity\log_operator
     */
    public function getOperator()
    {
        return $this->operator;
    }
}
