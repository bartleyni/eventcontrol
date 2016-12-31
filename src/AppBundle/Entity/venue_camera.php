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

}