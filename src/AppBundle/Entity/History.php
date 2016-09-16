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
     * @ORM\ManyToOne(targetEntity="Alert", inversedBy="Queue")
     * @ORM\JoinColumn(name="Alert_id", referencedColumnName="id")
     */
    private $Alert;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $dismissed;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="Queue")
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
     * Set Dismissed
     *
     * @param \DateTime $dismissed
     * @ORM\PrePersist
     * @return Queue
     */
    public function setDismissed()
    {

        if(!$this->dismissed){
            $this->dismissed = new \DateTime();
        }

        return $this;
    }
    
    /**
     * Get Dismissed
     *
     * @return \DateTime
     */
    public function getDismissed()
    {
        return $this->dismissed;
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
