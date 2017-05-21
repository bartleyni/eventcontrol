<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of log_entries
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Xiidea\EasyAuditBundle\Annotation\ORMSubscribedEvents;

/**
 * @ORM\Entity
 * @ORM\Table(name="log_entries")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\log_entriesRepository")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 * @ORMSubscribedEvents()
 */

class log_entries {
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $ref;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $log_timestamp;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $log_update_timestamp;
    
    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $log_blurb;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="log_entries")
     * @ORM\JoinColumn(name="operator", referencedColumnName="id")
     */
    private $operator;
    
    /**
     * @ORM\ManyToOne(targetEntity="event", inversedBy="log_entries")
     * @ORM\JoinColumn(name="event", referencedColumnName="id")
     */
    private $event;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $log_entry_open_time;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $location;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $reported_by;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $park_alert;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $latitude;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $longitude;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $geolocated;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="log_support_file", fileNameProperty="log_supportFileName")
     * 
     * @var File
     */
    private $log_supportFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $log_supportFileName;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $log_supportUpdatedAt;
    
    
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
     * Get ref
     *
     * @return integer
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set logTimestamp
     *
     * @param \DateTime $logTimestamp
     *
     * @return log_entries
     */
    public function setLogTimestamp($logTimestamp)
    {
        $this->log_timestamp = $logTimestamp;

        return $this;
    }

    /**
     * Get logTimestamp
     *
     * @return \DateTime
     */
    public function getLogTimestamp()
    {
        return $this->log_timestamp;
    }

    /**
     * Set logUpdateTimestamp
     *
     * @param \DateTime $logUpdateTimestamp
     *
     * @return log_entries
     */
    public function setLogUpdateTimestamp($logUpdateTimestamp)
    {
        $this->log_update_timestamp = $logUpdateTimestamp;

        return $this;
    }

    /**
     * Get logUpdateTimestamp
     *
     * @return \DateTime
     */
    public function getLogUpdateTimestamp()
    {
        return $this->log_update_timestamp;
    }
    
    
    /**
     * Set logBlurb
     *
     * @param string $logBlurb
     *
     * @return log_entries
     */
    public function setLogBlurb($logBlurb)
    {
        $this->log_blurb = $logBlurb;

        return $this;
    }

    /**
     * Get logBlurb
     *
     * @return string
     */
    public function getLogBlurb()
    {
        return $this->log_blurb;
    }

    /**
     * Set logEntryOpenTime
     *
     * @param \DateTime $logEntryOpenTime
     *
     * @return log_entries
     */
    public function setLogEntryOpenTime($logEntryOpenTime)
    {
        $this->log_entry_open_time = $logEntryOpenTime;

        return $this;
    }

    /**
     * Get logEntryOpenTime
     *
     * @return \DateTime
     */
    public function getLogEntryOpenTime()
    {
        return $this->log_entry_open_time;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return log_entries
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set reportedBy
     *
     * @param string $reportedBy
     *
     * @return log_entries
     */
    public function setReportedBy($reportedBy)
    {
        $this->reported_by = $reportedBy;

        return $this;
    }

    /**
     * Get reportedBy
     *
     * @return string
     */
    public function getReportedBy()
    {
        return $this->reported_by;
    }

    /**
     * Set operator
     *
     * @param \AppBundle\Entity\User $operator
     *
     * @return log_entries
     */
    public function setOperator(\AppBundle\Entity\User $operator = null)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return \AppBundle\Entity\User
     */
    public function getOperator()
    {
        return $this->operator;
    }
    
    /**
     * Set event
     *
     * @param \AppBundle\Entity\event $event
     *
     * @return log_entries
     */
    public function setEvent(\AppBundle\Entity\event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \AppBundle\Entity\event
     */
    public function getEvent()
    {
        return $this->event;
    }
    
    /** Set park_alert
     *
     * @param boolean $park_alert
     *
     * @return log_entries
     */
    public function setParkAlert($park_alert)
    {
        $this->park_alert = $park_alert;

        return $this;
    }

    /**
     * Get park_alert
     *
     * @return boolean
     */
    public function getParkAlert()
    {
        return $this->park_alert;
    }
    
    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return log_entries
     */
    public function setLatitude($latitude)
    {
//        if($this->id)
//        {
//            //Do nothing
//        } else {
            $this->latitude = $latitude;
//        }
        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
    
    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return log_entries
     */
    public function setLongitude($longitude)
    {
//        if($this->id)
//        {
//            //Do nothing
//        } else {
            $this->longitude = $longitude;
//        }
        
        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
    
        /** Set geolocated
     *
     * @param boolean $geolocated
     *
     * @return log_entries
     */
    public function setGeolocated($geolocated)
    {
        $this->geolocated = $geolocated;

        return $this;
    }

    /**
     * Get geolocated
     *
     * @return boolean
     */
    public function getGeolocated()
    {
        return $this->geolocated;
    }
    
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return event
     */
    public function setLogSupportFile(File $file = null)
    {
        $this->log_supportFile = $file;

        if ($file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->log_supportUpdatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getLogSupportFile()
    {
        return $this->log_supportFile;
    }

    /**
     * @param string $fileName
     *
     * @return event
     */
    public function setLogSupportFileName($fileName)
    {
        $this->log_supportFileName = $fileName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLogSupportFileName()
    {
        return $this->log_supportFileName;
    }

    /**
     * Set logSupportUpdatedAt
     *
     * @param \DateTime $logSupportUpdatedAt
     *
     * @return log_entries
     */
    public function setLogSupportUpdatedAt($logSupportUpdatedAt)
    {
        $this->log_supportUpdatedAt = $logSupportUpdatedAt;

        return $this;
    }

    /**
     * Get logSupportUpdatedAt
     *
     * @return \DateTime
     */
    public function getLogSupportUpdatedAt()
    {
        return $this->log_supportUpdatedAt;
    }
}
