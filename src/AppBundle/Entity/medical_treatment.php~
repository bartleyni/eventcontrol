<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of medical_treatment
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="medical_treatment")
 */

class medical_treatment {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
 
    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $medical_treatment_description;
    
    /**
     * @ORM\OneToMany(targetEntity="medical_log", mappedBy="medical_treatment")
     */
    private $medical_logs;
    
    public function __construct()
    {
        $this->medical_logs = new ArrayCollection();
    }
    
    public function __toString()
    {
        return (string) $this->medical_treatment_description;
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
     * Set medicalTreatmentDescription
     *
     * @param string $medicalTreatmentDescription
     *
     * @return medical_treatment
     */
    public function setMedicalTreatmentDescription($medicalTreatmentDescription)
    {
        $this->medical_treatment_description = $medicalTreatmentDescription;

        return $this;
    }

    /**
     * Get medicalTreatmentDescription
     *
     * @return string
     */
    public function getMedicalTreatmentDescription()
    {
        return $this->medical_treatment_description;
    }

    /**
     * Add medicalLog
     *
     * @param \AppBundle\Entity\medical_log $medicalLog
     *
     * @return medical_treatment
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
