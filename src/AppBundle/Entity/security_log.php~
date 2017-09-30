<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SecurityLog
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Xiidea\EasyAuditBundle\Annotation\ORMSubscribedEvents;
use AppBundle\Entity\LogListener;

/**
 * @ORM\Entity
 * @ORM\Table(name="security_log")
 * @ORMSubscribedEvents()
 * @ORM\HasLifecycleCallbacks
 */

class security_log {
     /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToOne(targetEntity="log_entries")
     * @ORM\JoinColumn(name="log_entry_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $log_entry_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="security_log_type", inversedBy="security_logs")
     * @ORM\JoinColumn(name="security_log_type_id", referencedColumnName="id")
     */
    private $security_log_type;
    
    /**
     * @ORM\ManyToOne(targetEntity="security_incident_type", inversedBy="security_logs")
     * @ORM\JoinColumn(name="security_incident_type_id", referencedColumnName="id", nullable=false)
     */
    private $security_incident_type;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $security_description;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $security_dispatched;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $security_responded;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $security_resolution;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $security_entry_closed_time;

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
     * Set securityDescription
     *
     * @param string $securityDescription
     *
     * @return security_log
     */
    public function setSecurityDescription($securityDescription)
    {
        $this->security_description = $securityDescription;

        return $this;
    }

    /**
     * Get securityDescription
     *
     * @return string
     */
    public function getSecurityDescription()
    {
        return $this->security_description;
    }

    /**
     * Set securityDispatched
     *
     * @param boolean $securityDispatched
     *
     * @return security_log
     */
    public function setSecurityDispatched($securityDispatched)
    {
        $this->security_dispatched = $securityDispatched;

        return $this;
    }

    /**
     * Get securityDispatched
     *
     * @return boolean
     */
    public function getSecurityDispatched()
    {
        return $this->security_dispatched;
    }

    /**
     * Set securityResponded
     *
     * @param boolean $securityResponded
     *
     * @return security_log
     */
    public function setSecurityResponded($securityResponded)
    {
        $this->security_responded = $securityResponded;

        return $this;
    }

    /**
     * Get securityResponded
     *
     * @return boolean
     */
    public function getSecurityResponded()
    {
        return $this->security_responded;
    }

    /**
     * Set securityResolution
     *
     * @param string $securityResolution
     *
     * @return security_log
     */
    public function setSecurityResolution($securityResolution)
    {
        $this->security_resolution = $securityResolution;

        return $this;
    }

    /**
     * Get securityResolution
     *
     * @return string
     */
    public function getSecurityResolution()
    {
        return $this->security_resolution;
    }

    /**
     * Set securityEntryClosedTime
     *
     * @param \DateTime $securityEntryClosedTime
     *
     * @return security_log
     */
    public function setSecurityEntryClosedTime($securityEntryClosedTime)
    {
        $this->security_entry_closed_time = $securityEntryClosedTime;

        return $this;
    }

    /**
     * Get securityEntryClosedTime
     *
     * @return \DateTime
     */
    public function getSecurityEntryClosedTime()
    {
        return $this->security_entry_closed_time;
    }

    /**
     * Set logEntryId
     *
     * @param \AppBundle\Entity\log_entries $logEntryId
     *
     * @return security_log
     */
    public function setLogEntryId(\AppBundle\Entity\log_entries $logEntryId = null)
    {
        $this->log_entry_id = $logEntryId;

        return $this;
    }

    /**
     * Get logEntryId
     *
     * @return \AppBundle\Entity\log_entries
     */
    public function getLogEntryId()
    {
        return $this->log_entry_id;
    }

    /**
     * Set securityLogType
     *
     * @param \AppBundle\Entity\security_log_type $securityLogType
     *
     * @return security_log
     */
    public function setSecurityLogType(\AppBundle\Entity\security_log_type $securityLogType = null)
    {
        $this->security_log_type = $securityLogType;

        return $this;
    }

    /**
     * Get securityLogType
     *
     * @return \AppBundle\Entity\security_log_type
     */
    public function getSecurityLogType()
    {
        return $this->security_log_type;
    }

    /**
     * Set securityIncidentType
     *
     * @param \AppBundle\Entity\security_incident_type $securityIncidentType
     *
     * @return security_log
     */
    public function setSecurityIncidentType(\AppBundle\Entity\security_incident_type $securityIncidentType)
    {
        $this->security_incident_type = $securityIncidentType;

        return $this;
    }

    /**
     * Get securityIncidentType
     *
     * @return \AppBundle\Entity\security_incident_type
     */
    public function getSecurityIncidentType()
    {
        return $this->security_incident_type;
    }
}
