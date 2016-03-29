<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SecurityLogType
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="security_log_type")
 */

class security_log_type {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
 
    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $security_type_description;
    
    /**
     * @ORM\OneToMany(targetEntity="security_log", mappedBy="security_log_type")
     */
    private $security_logs;
    
    public function __construct()
    {
        $this->security_logs = new ArrayCollection();
    }
    
    public function __toString()
    {
        return (string) $this->security_type_description;
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
     * Set securityTypeDescription
     *
     * @param string $securityTypeDescription
     *
     * @return security_log_type
     */
    public function setSecurityTypeDescription($securityTypeDescription)
    {
        $this->security_type_description = $securityTypeDescription;

        return $this;
    }

    /**
     * Get securityTypeDescription
     *
     * @return string
     */
    public function getSecurityTypeDescription()
    {
        return $this->security_type_description;
    }

    /**
     * Add securityLog
     *
     * @param \AppBundle\Entity\security_log $securityLog
     *
     * @return security_log_type
     */
    public function addSecurityLog(\AppBundle\Entity\security_log $securityLog)
    {
        $this->security_logs[] = $securityLog;

        return $this;
    }

    /**
     * Remove securityLog
     *
     * @param \AppBundle\Entity\security_log $securityLog
     */
    public function removeSecurityLog(\AppBundle\Entity\security_log $securityLog)
    {
        $this->security_logs->removeElement($securityLog);
    }

    /**
     * Get securityLogs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSecurityLogs()
    {
        return $this->security_logs;
    }
}
