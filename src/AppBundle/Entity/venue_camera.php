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
     * @ORM\Column(type="integer")
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
    protected $inverse = 0;


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
     * Set cameraId
     *
     * @param integer $cameraId
     *
     * @return venue_camera
     */
    public function setCameraId($cameraId)
    {
        $this->camera_id = $cameraId;

        return $this;
    }

    /**
     * Get cameraId
     *
     * @return integer
     */
    public function getCameraId()
    {
        return $this->camera_id;
    }

    /**
     * Set venueId
     *
     * @param integer $venueId
     *
     * @return venue_camera
     */
    public function setVenueId($venueId)
    {
        $this->venue_id = $venueId;

        return $this;
    }

    /**
     * Get venueId
     *
     * @return integer
     */
    public function getVenueId()
    {
        return $this->venue_id;
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
}
