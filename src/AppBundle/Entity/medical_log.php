<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of medical_log
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Xiidea\EasyAuditBundle\Annotation\ORMSubscribedEvents;
use AppBundle\Entity\LogListener;

/**
 * @ORM\Entity
 * @ORM\Table(name="medical_log")
 * @ORMSubscribedEvents()
 * @ORM\HasLifecycleCallbacks
 */

class medical_log {
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
     * @ORM\ManyToOne(targetEntity="medical_reported_injury_type", inversedBy="medical_logs")
     * @ORM\JoinColumn(name="medical_reported_injury_type_id", referencedColumnName="id")
     */
    private $medical_reported_injury_type;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $medical_description;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $medics_informed;
    
    /**
     * @ORM\ManyToOne(targetEntity="medical_response", inversedBy="medical_logs")
     * @ORM\JoinColumn(name="medical_response_id", referencedColumnName="id", nullable=true)
     */
    private $medical_response;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $nine_nine_nine_required;

    /**
     * @ORM\ManyToOne(targetEntity="medical_treatment", inversedBy="medical_logs")
     * @ORM\JoinColumn(name="medical_treatment_id", referencedColumnName="id", nullable=true)
     */
    private $medical_treatment;
 
    /**
     * @ORM\ManyToOne(targetEntity="medical_resolution", inversedBy="medical_logs")
     * @ORM\JoinColumn(name="medical_resolution_id", referencedColumnName="id", nullable=true)
     */
    private $medical_resolution;

    /**
     * @ORM\Column(type="text", nullable=true, nullable=true)
     */
    protected $medical_notes;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $medical_entry_closed_time;

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
     * Set medicalDescription
     *
     * @param string $medicalDescription
     *
     * @return medical_log
     */
    public function setMedicalDescription($medicalDescription)
    {
        $this->medical_description = $medicalDescription;

        return $this;
    }

    /**
     * Get medicalDescription
     *
     * @return string
     */
    public function getMedicalDescription()
    {
        return $this->medical_description;
    }

    /**
     * Set medicsInformed
     *
     * @param boolean $medicsInformed
     *
     * @return medical_log
     */
    public function setMedicsInformed($medicsInformed)
    {
        $this->medics_informed = $medicsInformed;

        return $this;
    }

    /**
     * Get medicsInformed
     *
     * @return boolean
     */
    public function getMedicsInformed()
    {
        return $this->medics_informed;
    }

    /**
     * Set nineNineNineRequired
     *
     * @param boolean $nineNineNineRequired
     *
     * @return medical_log
     */
    public function setNineNineNineRequired($nineNineNineRequired)
    {
        $this->nine_nine_nine_required = $nineNineNineRequired;

        return $this;
    }

    /**
     * Get nineNineNineRequired
     *
     * @return boolean
     */
    public function getNineNineNineRequired()
    {
        return $this->nine_nine_nine_required;
    }

    /**
     * Set medicalNotes
     *
     * @param string $medicalNotes
     *
     * @return medical_log
     */
    public function setMedicalNotes($medicalNotes)
    {
        $this->medical_notes = $medicalNotes;

        return $this;
    }

    /**
     * Get medicalNotes
     *
     * @return string
     */
    public function getMedicalNotes()
    {
        return $this->medical_notes;
    }

    /**
     * Set medicalEntryClosedTime
     *
     * @param \DateTime $medicalEntryClosedTime
     *
     * @return medical_log
     */
    public function setMedicalEntryClosedTime($medicalEntryClosedTime)
    {
        $this->medical_entry_closed_time = $medicalEntryClosedTime;

        return $this;
    }

    /**
     * Get medicalEntryClosedTime
     *
     * @return \DateTime
     */
    public function getMedicalEntryClosedTime()
    {
        return $this->medical_entry_closed_time;
    }

    /**
     * Set logEntryId
     *
     * @param \AppBundle\Entity\log_entries $logEntryId
     *
     * @return medical_log
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
     * Set medicalReportedInjuryType
     *
     * @param \AppBundle\Entity\medical_reported_injury_type $medicalReportedInjuryType
     *
     * @return medical_log
     */
    public function setMedicalReportedInjuryType(\AppBundle\Entity\medical_reported_injury_type $medicalReportedInjuryType = null)
    {
        $this->medical_reported_injury_type = $medicalReportedInjuryType;

        return $this;
    }

    /**
     * Get medicalReportedInjuryType
     *
     * @return \AppBundle\Entity\medical_reported_injury_type
     */
    public function getMedicalReportedInjuryType()
    {
        return $this->medical_reported_injury_type;
    }

    /**
     * Set medicalResponse
     *
     * @param \AppBundle\Entity\medical_response $medicalResponse
     *
     * @return medical_log
     */
    public function setMedicalResponse(\AppBundle\Entity\medical_response $medicalResponse = null)
    {
        $this->medical_response = $medicalResponse;

        return $this;
    }

    /**
     * Get medicalResponse
     *
     * @return \AppBundle\Entity\medical_response
     */
    public function getMedicalResponse()
    {
        return $this->medical_response;
    }

    /**
     * Set medicalTreatment
     *
     * @param \AppBundle\Entity\medical_treatment $medicalTreatment
     *
     * @return medical_log
     */
    public function setMedicalTreatment(\AppBundle\Entity\medical_treatment $medicalTreatment = null)
    {
        $this->medical_treatment = $medicalTreatment;

        return $this;
    }

    /**
     * Get medicalTreatment
     *
     * @return \AppBundle\Entity\medical_treatment
     */
    public function getMedicalTreatment()
    {
        return $this->medical_treatment;
    }

    /**
     * Set medicalResolution
     *
     * @param \AppBundle\Entity\medical_resolution $medicalResolution
     *
     * @return medical_log
     */
    public function setMedicalResolution(\AppBundle\Entity\medical_resolution $medicalResolution = null)
    {
        $this->medical_resolution = $medicalResolution;

        return $this;
    }

    /**
     * Get medicalResolution
     *
     * @return \AppBundle\Entity\medical_resolution
     */
    public function getMedicalResolution()
    {
        return $this->medical_resolution;
    }
}
