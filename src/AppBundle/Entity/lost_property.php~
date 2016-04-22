<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of lost_property
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="lost_property")
 */

class lost_property {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToOne(targetEntity="log_entries")
     * @ORM\JoinColumn(name="log_entry_id", referencedColumnName="id", nullable=true)
     */
    private $log_entry_id;
 
    /**
     * @ORM\Column(type="boolean")
     */
    protected $lost_property_item_lost;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $lost_property_item_found;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $lost_property_description;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $lost_property_contact_details;
    
    /**
     * @ORM\ManyToOne(targetEntity="lost_property_resolution", inversedBy="lost_property_logs")
     * @ORM\JoinColumn(name="lost_property_resolution_id", referencedColumnName="id", nullable=true)
     */
    private $lost_property_resolution;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $lost_property_resolution_description;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lost_property_entry_closed_time;

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
     * Set lostPropertyItemLost
     *
     * @param boolean $lostPropertyItemLost
     *
     * @return lost_property
     */
    public function setLostPropertyItemLost($lostPropertyItemLost)
    {
        $this->lost_property_item_lost = $lostPropertyItemLost;

        return $this;
    }

    /**
     * Get lostPropertyItemLost
     *
     * @return boolean
     */
    public function getLostPropertyItemLost()
    {
        return $this->lost_property_item_lost;
    }

    /**
     * Set lostPropertyItemFound
     *
     * @param boolean $lostPropertyItemFound
     *
     * @return lost_property
     */
    public function setLostPropertyItemFound($lostPropertyItemFound)
    {
        $this->lost_property_item_found = $lostPropertyItemFound;

        return $this;
    }

    /**
     * Get lostPropertyItemFound
     *
     * @return boolean
     */
    public function getLostPropertyItemFound()
    {
        return $this->lost_property_item_found;
    }

    /**
     * Set lostPropertyDescription
     *
     * @param string $lostPropertyDescription
     *
     * @return lost_property
     */
    public function setLostPropertyDescription($lostPropertyDescription)
    {
        $this->lost_property_description = $lostPropertyDescription;

        return $this;
    }

    /**
     * Get lostPropertyDescription
     *
     * @return string
     */
    public function getLostPropertyDescription()
    {
        return $this->lost_property_description;
    }

    /**
     * Set lostPropertyContactDetails
     *
     * @param string $lostPropertyContactDetails
     *
     * @return lost_property
     */
    public function setLostPropertyContactDetails($lostPropertyContactDetails)
    {
        $this->lost_property_contact_details = $lostPropertyContactDetails;

        return $this;
    }

    /**
     * Get lostPropertyContactDetails
     *
     * @return string
     */
    public function getLostPropertyContactDetails()
    {
        return $this->lost_property_contact_details;
    }

    /**
     * Set lostPropertyResolutionDescription
     *
     * @param string $lostPropertyResolutionDescription
     *
     * @return lost_property
     */
    public function setLostPropertyResolutionDescription($lostPropertyResolutionDescription)
    {
        $this->lost_property_resolution_description = $lostPropertyResolutionDescription;

        return $this;
    }

    /**
     * Get lostPropertyResolutionDescription
     *
     * @return string
     */
    public function getLostPropertyResolutionDescription()
    {
        return $this->lost_property_resolution_description;
    }

    /**
     * Set lostPropertyEntryClosedTime
     *
     * @param \DateTime $lostPropertyEntryClosedTime
     *
     * @return lost_property
     */
    public function setLostPropertyEntryClosedTime($lostPropertyEntryClosedTime)
    {
        $this->lost_property_entry_closed_time = $lostPropertyEntryClosedTime;

        return $this;
    }

    /**
     * Get lostPropertyEntryClosedTime
     *
     * @return \DateTime
     */
    public function getLostPropertyEntryClosedTime()
    {
        return $this->lost_property_entry_closed_time;
    }

    /**
     * Set logEntryId
     *
     * @param \AppBundle\Entity\log_entries $logEntryId
     *
     * @return lost_property
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
     * Set lostPropertyResolution
     *
     * @param \AppBundle\Entity\lost_property_resolution $lostPropertyResolution
     *
     * @return lost_property
     */
    public function setLostPropertyResolution(\AppBundle\Entity\lost_property_resolution $lostPropertyResolution = null)
    {
        $this->lost_property_resolution = $lostPropertyResolution;

        return $this;
    }

    /**
     * Get lostPropertyResolution
     *
     * @return \AppBundle\Entity\lost_property_resolution
     */
    public function getLostPropertyResolution()
    {
        return $this->lost_property_resolution;
    }
}
