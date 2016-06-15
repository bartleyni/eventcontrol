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
 * @ORM\Entity(repositoryClass="AppBundle\Entity\skewRepository")
 * @ORM\HasLifecycleCallbacks
 */


class skew {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $timestamp;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $skew_in;

    /**
     * @ORM\Column(type="integer")
     */
    protected $skew_out;
    /**
     * @ORM\ManyToOne(targetEntity="venue")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id")
     */
    protected $venue_id;


    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @ORM\PrePersist
     * @return Example
     */
    public function setTimestamp()
    {

        if(!$this->timestamp){
            $this->timestamp = new \DateTime();
        }

        return $this;
    }

}
