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
 * @ORM\Entity
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
     * @ORM\Column(type="text")
     */
    protected $name;

     /**
      * @ORM\OneToMany(targetEntity="camera", mappedBy="venue")
     */
    protected $cameras;

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
}
