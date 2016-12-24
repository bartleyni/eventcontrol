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
     * @ORM\ManyToMany(targetEntity="venue", mappedBy="cameras")
     */
    private $venues;

    public function __construct()
    {
        $this->venues = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add venue
     *
     * @param \AppBundle\Entity\venue $venue
     *
     * @return camera
     */
    public function addVenue(\AppBundle\Entity\venue $venue)
    {
        $this->venues[] = $venue;

        return $this;
    }

    /**
     * Remove venue
     *
     * @param \AppBundle\Entity\venue $venue
     */
    public function removeVenue(\AppBundle\Entity\venue $venue)
    {
        $this->venues->removeElement($venue);
    }

    /**
     * Get venues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVenues()
    {
        return $this->venues;
    }
}
