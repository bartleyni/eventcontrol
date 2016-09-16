<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="queue")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class Queue
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Alert", inversedBy="Queue")
     * @ORM\JoinColumn(name="Alert_id", referencedColumnName="id")
     */
    private $Alert;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $viewed;
    
    public function __toString()
    {
        return (string) $this->getId();
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
     * Set Viewed
     *
     * @param \DateTime $viewed
     * @return Queue
     */
    public function setViewed()
    {

        if(!$this->viewed){
            $this->viewed = new \DateTime();
        }

        return $this;
    }
    
    /**
     * Get Viewed
     *
     * @return \DateTime
     */
    public function getViewed()
    {
        return $this->viewed;
    }
    
    /**
     * Set Alert
     *
     * @param \AppBundle\Entity\Alert $alert
     *
     * @return Queue
     */
    public function setAlert(\AppBundle\Entity\Alert $alert = null)
    {
        $this->Alert = $alert;

        return $this;
    }

    /**
     * Get Alert
     *
     * @return \AppBundle\Entity\Alert
     */
    public function getAlert()
    {
        return $this->Alert;
    }
}
