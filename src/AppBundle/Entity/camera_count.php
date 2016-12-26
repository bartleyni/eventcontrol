<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of camera_count
 *
 *
 * @author Matthew
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\camera_countRepository")
 * @ORM\Table(name="camera_count")
 * @ORM\HasLifecycleCallbacks
 */

class camera_count {


    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="camera", inversedBy="camera_count")
     * @ORM\JoinColumn(name="camera_id", referencedColumnName="id")
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
     * calculat running total
     *
     * @ORM\PrePersist
     */
    public function calculate_running_total($args)
    {
        $Camera = $args->getEntityManager()->createQueryBuilder()->select('Camera_count')
            ->from("AppBundle\Entity\camera_count", 'Camera_count')
            ->orderBy('Camera_count.id', 'DESC')
            ->where('Camera_count.camera_id = ?1')
            ->setParameter(1, $this->camera_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getScalarResult();
        //print_r($this);


        $diffrance_in = $this->count_in - $Camera[0]['Camera_count_count_in'];
        $diffrance_out = $this->count_out - $Camera[0]['Camera_count_count_out'];

        if($diffrance_out < 0){
            $this->running_count_in = $this->count_in + $Camera[0]['Camera_count_running_count_in'];
        }else{
            $this->running_count_in = $Camera[0]['Camera_count_running_count_in'] + $diffrance_in;
        }

        if($diffrance_out < 0){
            $this->running_count_out = $this->count_out + $Camera[0]['Camera_count_running_count_out'];
        }else{
            $this->running_count_out = $Camera[0]['Camera_count_running_count_out'] + $diffrance_out;
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

    /**
     * Set runningCountIn
     *
     * @param integer $runningCountIn
     *
     * @return camera
     */
    public function setRunningCountIn($runningCountIn)
    {
        $this->running_count_in = $runningCountIn;

        return $this;
    }

    /**
     * Get runningCountIn
     *
     * @return integer
     */
    public function getRunningCountIn()
    {
        return $this->running_count_in;
    }

    /**
     * Set runningCountOut
     *
     * @param integer $runningCountOut
     *
     * @return camera
     */
    public function setRunningCountOut($runningCountOut)
    {
        $this->running_count_out = $runningCountOut;

        return $this;
    }

    /**
     * Get runningCountOut
     *
     * @return integer
     */
    public function getRunningCountOut()
    {
        return $this->running_count_out;
    }
}
