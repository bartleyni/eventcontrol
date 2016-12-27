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

    public function setCount($count)
    {
        $this->count[] = $count;

        return $this;
    }
}