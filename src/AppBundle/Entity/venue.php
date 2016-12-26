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

}