<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of medical_reported_injury_type
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="medical_reported_injury_type")
 */

class medical_reported_injury_type {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
 
    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $medical_injury_description;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $medical_severity;
    
    
    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    protected $medical_injury_colour;
    
    /**
     * @ORM\OneToMany(targetEntity="medical_log", mappedBy="medical_reported_injury_type")
     */
    private $medical_logs;
    
    public function __construct()
    {
        $this->medical_logs = new ArrayCollection();
    }
    
    public function __toString()
    {
        return (string) $this->medical_injury_description;
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
     * Set medicalInjuryDescription
     *
     * @param string $medicalInjuryDescription
     *
     * @return medical_reported_injury_type
     */
    public function setMedicalInjuryDescription($medicalInjuryDescription)
    {
        $this->medical_injury_description = $medicalInjuryDescription;

        return $this;
    }

    /**
     * Get medicalInjuryDescription
     *
     * @return string
     */
    public function getMedicalInjuryDescription()
    {
        return $this->medical_injury_description;
    }

    /**
     * Set medicalSeverity
     *
     * @param integer $medicalSeverity
     *
     * @return medical_reported_injury_type
     */
    public function setMedicalSeverity($medicalSeverity)
    {
        $this->medical_severity = $medicalSeverity;

        return $this;
    }

    /**
     * Get medicalSeverity
     *
     * @return integer
     */
    public function getMedicalSeverity()
    {
        return $this->medical_severity;
    }

    /**
     * Set medicalInjuryColour
     *
     * @param string $medicalInjuryColour
     *
     * @return medical_reported_injury_type
     */
    public function setMedicalInjuryColour($medicalInjuryColour)
    {
        $this->medical_injury_colour = $medicalInjuryColour;

        return $this;
    }

    /**
     * Get medicalInjuryColour
     *
     * @return string
     */
    public function getMedicalInjuryColour()
    {
        return $this->medical_injury_colour;
    }

    /**
     * Add medicalLog
     *
     * @param \AppBundle\Entity\medical_log $medicalLog
     *
     * @return medical_reported_injury_type
     */
    public function addMedicalLog(\AppBundle\Entity\medical_log $medicalLog)
    {
        $this->medical_logs[] = $medicalLog;

        return $this;
    }

    /**
     * Remove medicalLog
     *
     * @param \AppBundle\Entity\medical_log $medicalLog
     */
    public function removeMedicalLog(\AppBundle\Entity\medical_log $medicalLog)
    {
        $this->medical_logs->removeElement($medicalLog);
    }

    /**
     * Get medicalLogs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedicalLogs()
    {
        return $this->medical_logs;
    }
}
