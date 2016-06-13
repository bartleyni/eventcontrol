<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UPS
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="event")
 */

class UPS {
    
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
     * @ORM\Column(type="string", length=200)
     */
    protected $power;
    
    /**
     * @ORM\OneToMany(targetEntity="UPS_status", mappedBy="UPS")
     */
    private $UPS_Status;
    
    public function __toString()
    {
        return (string) $this->getName();
    }
}
