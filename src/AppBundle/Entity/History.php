<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="history")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class History
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Alert")
     * @ORM\JoinColumn(name="Alert_id", referencedColumnName="id")
     */
    private $Alert;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $acknowledged;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="User_id", referencedColumnName="id")
     */
    private $Operator;
    
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
     * Set Acknowledged
     *
     * @param \DateTime $acknowledged
     * @ORM\PrePersist
     * @return Queue
     */
    public function setAcknowledged()
    {

        if(!$this->acknowledged){
            $this->acknowledged = new \DateTime();
        }

        return $this;
    }
    
    /**
     * Get Acknowledged
     *
     * @return \DateTime
     */
    public function getAcknowledged()
    {
        return $this->acknowledged;
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
    
    /**
     * Set Operator
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Queue
     */
    public function setOperator(\AppBundle\Entity\User $user = null)
    {
        $this->Operator = $user;

        return $this;
    }

    /**
     * Get Operator
     *
     * @return \AppBundle\Entity\User
     */
    public function getOperator()
    {
        return $this->Operator;
    }
}
