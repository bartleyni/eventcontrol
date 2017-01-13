<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of camera
 *
 *
 * @author Matthew
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\cameraRepository")
 * @ORM\Table(name="camera")
 * @ORM\HasLifecycleCallbacks
 */

class camera {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $location;
    /**
     * @ORM\OneToMany(targetEntity="venue_camera", mappedBy="camera_id")
     */
    protected $venue_camera;
    /**
     * @ORM\OneToMany(targetEntity="camera_count", mappedBy="camera_id")
     */
    protected $camera_count;

    public function __toString()
    {
        return (string) $this->getName()." - ".$this->getLocation();
    }

    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->venue_camera = new \Doctrine\Common\Collections\ArrayCollection();
        $this->camera_count = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return camera
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return camera
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
     * Add venueCamera
     *
     * @param \AppBundle\Entity\venue_camera $venueCamera
     *
     * @return camera
     */
    public function addVenueCamera(\AppBundle\Entity\venue_camera $venueCamera)
    {
        $this->venue_camera[] = $venueCamera;

        return $this;
    }

    /**
     * Remove venueCamera
     *
     * @param \AppBundle\Entity\venue_camera $venueCamera
     */
    public function removeVenueCamera(\AppBundle\Entity\venue_camera $venueCamera)
    {
        $this->venue_camera->removeElement($venueCamera);
    }

    /**
     * Get venueCamera
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVenueCamera()
    {
        return $this->venue_camera;
    }

    /**
     * Add cameraCount
     *
     * @param \AppBundle\Entity\camera_count $cameraCount
     *
     * @return camera
     */
    public function addCameraCount(\AppBundle\Entity\camera_count $cameraCount)
    {
        $this->camera_count[] = $cameraCount;

        return $this;
    }

    /**
     * Remove cameraCount
     *
     * @param \AppBundle\Entity\camera_count $cameraCount
     */
    public function removeCameraCount(\AppBundle\Entity\camera_count $cameraCount)
    {
        $this->camera_count->removeElement($cameraCount);
    }

    /**
     * Get cameraCount
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCameraCount()
    {
        return $this->camera_count;
    }
}
