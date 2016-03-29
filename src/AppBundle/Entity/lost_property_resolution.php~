<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of lost_property_resolution
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="lost_property_resolution")
 */

class lost_property_resolution {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
 
    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $lost_property_resolution_description;
    
    /**
     * @ORM\OneToMany(targetEntity="lost_property", mappedBy="lost_property_resolution")
     */
    private $lost_property_logs;
    
    public function __construct()
    {
        $this->lost_property_logs = new ArrayCollection();
    }
    
    public function __toString()
    {
        return (string) $this->lost_property_resolution_description;
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
     * Set lostPropertyResolutionDescription
     *
     * @param string $lostPropertyResolutionDescription
     *
     * @return lost_property_resolution
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
     * Add lostPropertyLog
     *
     * @param \AppBundle\Entity\lost_property $lostPropertyLog
     *
     * @return lost_property_resolution
     */
    public function addLostPropertyLog(\AppBundle\Entity\lost_property $lostPropertyLog)
    {
        $this->lost_property_logs[] = $lostPropertyLog;

        return $this;
    }

    /**
     * Remove lostPropertyLog
     *
     * @param \AppBundle\Entity\lost_property $lostPropertyLog
     */
    public function removeLostPropertyLog(\AppBundle\Entity\lost_property $lostPropertyLog)
    {
        $this->lost_property_logs->removeElement($lostPropertyLog);
    }

    /**
     * Get lostPropertyLogs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLostPropertyLogs()
    {
        return $this->lost_property_logs;
    }
}
