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
     * @ORM\Column(type="integer")
     */
    protected $venue_id;



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
}
