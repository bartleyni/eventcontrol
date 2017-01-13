<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of venue_camera
 *
 *
 * @author Matthew
 */
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table(name="venue_camera")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\venue_cameraRepository")
 */
class venue_camera {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     *
     * @ORM\ManyToOne(targetEntity="camera", inversedBy="venue_camera")
     * @ORM\JoinColumn(name="camera_id", referencedColumnName="id")
     *
     */
    protected $camera_id;
    /**
     * @ORM\ManyToOne(targetEntity="venue", inversedBy="venue_camera")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id")
     */
    protected $venue_id;
    /**
     * @ORM\Column(type="boolean", nullable=false, )
     */
    protected $inverse = FALSE;


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
     * Set inverse
     *
     * @param boolean $inverse
     *
     * @return venue_camera
     */
    public function setInverse($inverse)
    {
        $this->inverse = $inverse;

        return $this;
    }

    /**
     * Get inverse
     *
     * @return boolean
     */
    public function getInverse()
    {
        return $this->inverse;
    }

    /**
     * Set cameraId
     *
     * @param \AppBundle\Entity\camera $cameraId
     *
     * @return venue_camera
     */
    public function setCameraId(\AppBundle\Entity\camera $cameraId = null)
    {
        $this->camera_id = $cameraId;

        return $this;
    }

    /**
     * Get cameraId
     *
     * @return \AppBundle\Entity\camera
     */
    public function getCameraId()
    {
        return $this->camera_id;
    }

    /**
     * Set venueId
     *
     * @param \AppBundle\Entity\venue $venueId
     *
     * @return venue_camera
     */
    public function setVenueId(\AppBundle\Entity\venue $venueId = null)
    {
        $this->venue_id = $venueId;

        return $this;
    }

    /**
     * Get venueId
     *
     * @return \AppBundle\Entity\venue
     */
    public function getVenueId()
    {
        return $this->venue_id;
    }
}
