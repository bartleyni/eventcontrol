<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of venue
 *
 *
 * @author Matthew
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\venueRepository")
 * @ORM\Table(name="venue")
 */


class venue {


    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $doors;
    /**
     * @ORM\Column(type="text")
     */
    protected $name;


    /**
     * @ORM\ManyToMany(targetEntity="camera", inversedBy="venus")
     * @ORM\JoinTable(name="venue_camera")
     */
    protected $cameras;

    /**
     * @ORM\OneToMany(targetEntity="skew", mappedBy="venue_id")
     */
    protected $skew;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cameras = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return venue
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
     * Add camera
     *
     * @param \AppBundle\Entity\camera $camera
     *
     * @return venue
     */
    public function addCamera(\AppBundle\Entity\camera $camera)
    {
        $this->cameras[] = $camera;

        return $this;
    }

    /**
     * Remove camera
     *
     * @param \AppBundle\Entity\camera $camera
     */
    public function removeCamera(\AppBundle\Entity\camera $camera)
    {
        $this->cameras->removeElement($camera);
    }

    /**
     * Get cameras
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCameras()
    {
        return $this->cameras;
    }

    /**
     * Add venueCamera
     *
     * @param \AppBundle\Entity\venue_camera $venueCamera
     *
     * @return venue
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
     * Set doors
     *
     * @param \DateTime $doors
     *
     * @return venue
     */
    public function setDoors($doors)
    {
        $this->doors = $doors;

        return $this;
    }

    /**
     * Get doors
     *
     * @return \DateTime
     */
    public function getDoors()
    {
        return $this->doors;
    }

    /**
     * Add skew
     *
     * @param \AppBundle\Entity\skew $skew
     *
     * @return venue
     */
    public function addSkew(\AppBundle\Entity\skew $skew)
    {
        $this->skew[] = $skew;

        return $this;
    }

    /**
     * Remove skew
     *
     * @param \AppBundle\Entity\skew $skew
     */
    public function removeSkew(\AppBundle\Entity\skew $skew)
    {
        $this->skew->removeElement($skew);
    }

    /**
     * Get skew
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSkew()
    {
        return $this->skew;
    }
}
