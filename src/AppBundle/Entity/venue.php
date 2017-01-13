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
class venue
{
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
     * @ORM\OneToMany(targetEntity="venue_camera", mappedBy="venue_id")
     */
    protected $venue_camera;
    /**
     * @ORM\OneToMany(targetEntity="skew", mappedBy="venue_id")
     */
    protected $skew;

    /**
     * @ORM\ManyToMany(targetEntity="event", mappedBy="venues")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $event;

    public function __toString()
    {
        return (string) $this->getName();
    }
    
    public function __construct()
    {
        $this->event = new ArrayCollection();
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

    /**
     * Add event
     *
     * @param \AppBundle\Entity\event $event
     *
     * @return venue
     */
    public function addEvent(\AppBundle\Entity\event $event)
    {
        $this->event[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param \AppBundle\Entity\event $event
     */
    public function removeEvent(\AppBundle\Entity\event $event)
    {
        $this->event->removeElement($event);
    }

    /**
     * Get event
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvent()
    {
        return $this->event;
    }
}
