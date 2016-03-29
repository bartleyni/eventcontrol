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
 * @ORM\Table(name="log_operator")
 */

class log_operator {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
 
    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $operator_first_name;
    
    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $operator_last_name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $operator_phone;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $operator_email;
    
    /**
     * @ORM\OneToMany(targetEntity="log_entries", mappedBy="operator")
     */
    private $log_entries;
    
    public function __construct()
    {
        $this->log_operator = new ArrayCollection();
    }
    
    public function __toString()
    {
        return (string) $this->getOperatorFirstName()." ".$this->getOperatorLastName();
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
     * Set operatorFirstName
     *
     * @param string $operatorFirstName
     *
     * @return log_operator
     */
    public function setOperatorFirstName($operatorFirstName)
    {
        $this->operator_first_name = $operatorFirstName;

        return $this;
    }

    /**
     * Get operatorFirstName
     *
     * @return string
     */
    public function getOperatorFirstName()
    {
        return $this->operator_first_name;
    }

    /**
     * Set operatorLastName
     *
     * @param string $operatorLastName
     *
     * @return log_operator
     */
    public function setOperatorLastName($operatorLastName)
    {
        $this->operator_last_name = $operatorLastName;

        return $this;
    }

    /**
     * Get operatorLastName
     *
     * @return string
     */
    public function getOperatorLastName()
    {
        return $this->operator_last_name;
    }

    /**
     * Set operatorPhone
     *
     * @param string $operatorPhone
     *
     * @return log_operator
     */
    public function setOperatorPhone($operatorPhone)
    {
        $this->operator_phone = $operatorPhone;

        return $this;
    }

    /**
     * Get operatorPhone
     *
     * @return string
     */
    public function getOperatorPhone()
    {
        return $this->operator_phone;
    }

    /**
     * Set operatorEmail
     *
     * @param string $operatorEmail
     *
     * @return log_operator
     */
    public function setOperatorEmail($operatorEmail)
    {
        $this->operator_email = $operatorEmail;

        return $this;
    }

    /**
     * Get operatorEmail
     *
     * @return string
     */
    public function getOperatorEmail()
    {
        return $this->operator_email;
    }

    /**
     * Add logEntry
     *
     * @param \AppBundle\Entity\log_entries $logEntry
     *
     * @return log_operator
     */
    public function addLogEntry(\AppBundle\Entity\log_entries $logEntry)
    {
        $this->log_entries[] = $logEntry;

        return $this;
    }

    /**
     * Remove logEntry
     *
     * @param \AppBundle\Entity\log_entries $logEntry
     */
    public function removeLogEntry(\AppBundle\Entity\log_entries $logEntry)
    {
        $this->log_entries->removeElement($logEntry);
    }

    /**
     * Get logEntries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLogEntries()
    {
        return $this->log_entries;
    }
}
