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
     * @ORM\Column(type="integer")
     */
    protected $camera_id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $timestamp;

    /**
     * @ORM\Column(type="integer")
     */
    protected $count_in;

    /**
     * @ORM\Column(type="integer")
     */
    protected $count_out;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $running_count_in;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $running_count_out;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $doors;

    /**
     * @ORM\ManyToOne(targetEntity="venue")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id")
     */
    protected $venue;





    /**
     * calculat running total
     *
     * @ORM\PrePersist
     */
    public function calculate_running_total($args)
    {
        $Camera = $args->getEntityManager()->createQueryBuilder()->select('Camera')
            ->from("AppBundle\Entity\camera", 'Camera')
            ->orderBy('Camera.id', 'DESC')
            ->where('Camera.camera_id = ?1')
            ->setParameter(1, $this->camera_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getScalarResult();
        print_r($Camera);
        //print_r($this);


        $diffrance_in = $this->count_in - $Camera[0]['Camera_count_in'];
        $diffrance_out = $this->count_out - $Camera[0]['Camera_count_out'];

        if($diffrance_in < 0){
            $this->running_count_in = $this->count_in + $Camera[0]['Camera_running_count_in'];
        }else{
            $this->running_count_in = $Camera[0]['Camera_running_count_in'] + $diffrance_in;
        }

        if($diffrance_out < 0){
            $this->running_count_out = $this->count_out + $Camera[0]['Camera_running_count_out'];
        }else{
            $this->running_count_out = $Camera[0]['Camera_running_count_out'] + $diffrance_out;
        }
        

        //$this->running_count = $Camera[0]['Camera_id'];

        return $this;
    }

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
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }


    /**
     * Set runningCount
     *
     * @param integer $runningCount
     *
     * @return camera
     */
    public function setRunningCount($runningCount)
    {
        $this->running_count = $runningCount;

        return $this;
    }

    /**
     * Get runningCount
     *
     * @return integer
     */
    public function getRunningCount()
    {
        return $this->running_count;
    }

    /**
     * Set doors
     *
     * @param boolean $doors
     *
     * @return camera
     */
    public function setDoors($doors)
    {
        $this->doors = $doors;

        return $this;
    }

    /**
     * Get doors
     *
     * @return boolean
     */
    public function getDoors()
    {
        return $this->doors;
    }

    /**
     * Set venue
     *
     * @param \AppBundle\Entity\venue $venue
     *
     * @return camera
     */
    public function setVenue(\AppBundle\Entity\venue $venue = null)
    {
        $this->venue = $venue;

        return $this;
    }

    /**
     * Get venue
     *
     * @return \AppBundle\Entity\venue
     */
    public function getVenue()
    {
        return $this->venue;
    }


    /**
     * Set cameraId
     *
     * @param integer $cameraId
     *
     * @return camera
     */
    public function setCameraId($cameraId)
    {
        $this->camera_id = $cameraId;

        return $this;
    }

    /**
     * Get cameraId
     *
     * @return integer
     */
    public function getCameraId()
    {
        return $this->camera_id;
    }

    /**
     * Set countIn
     *
     * @param integer $countIn
     *
     * @return camera
     */
    public function setCountIn($countIn)
    {
        $this->count_in = $countIn;

        return $this;
    }

    /**
     * Get countIn
     *
     * @return integer
     */
    public function getCountIn()
    {
        return $this->count_in;
    }

    /**
     * Set countOut
     *
     * @param integer $countOut
     *
     * @return camera
     */
    public function setCountOut($countOut)
    {
        $this->count_out = $countOut;

        return $this;
    }

    /**
     * Get countOut
     *
     * @return integer
     */
    public function getCountOut()
    {
        return $this->count_out;
    }
}
