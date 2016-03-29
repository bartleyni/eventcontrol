<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SecurityIncidentType
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="security_incident_type")
 */

class security_incident_type {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
 
    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $security_incident_description;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $severity;
    
    
    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $security_incident_colour;
    
    /**
     * @ORM\OneToMany(targetEntity="security_log", mappedBy="security_incident_type")
     */
    private $security_logs;
    
    public function __construct()
    {
        $this->security_logs = new ArrayCollection();
    }
    
    public function __toString()
    {
        return (string) $this->security_incident_description;
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
     * Set securityIncidentDescription
     *
     * @param string $securityIncidentDescription
     *
     * @return security_incident_type
     */
    public function setSecurityIncidentDescription($securityIncidentDescription)
    {
        $this->security_incident_description = $securityIncidentDescription;

        return $this;
    }

    /**
     * Get securityIncidentDescription
     *
     * @return string
     */
    public function getSecurityIncidentDescription()
    {
        return $this->security_incident_description;
    }

    /**
     * Set severity
     *
     * @param integer $severity
     *
     * @return security_incident_type
     */
    public function setSeverity($severity)
    {
        $this->severity = $severity;

        return $this;
    }

    /**
     * Get severity
     *
     * @return integer
     */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
     * Set securityIncidentColour
     *
     * @param string $securityIncidentColour
     *
     * @return security_incident_type
     */
    public function setSecurityIncidentColour($securityIncidentColour)
    {
        $this->security_incident_colour = $securityIncidentColour;

        return $this;
    }

    /**
     * Get securityIncidentColour
     *
     * @return string
     */
    public function getSecurityIncidentColour()
    {
        return $this->security_incident_colour;
    }

    /**
     * Add securityLog
     *
     * @param \AppBundle\Entity\security_log $securityLog
     *
     * @return security_incident_type
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
