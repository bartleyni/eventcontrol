<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\AlertListener;

/**
 * @ORM\Table(name="alerts")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class Alert
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="string", length=200) */
    private $title;

    /** @ORM\Column(type="string", length=1000) */
    private $message;
    
    /** @ORM\Column(type="string", length=200, nullable=true) */
    private $url;
    
    /** @ORM\Column(type="string", length=50) */
    private $type;
    
    /** @ORM\Column(type="string", length=50, nullable=true) */
    private $fieldOfResponsibility;
    
    /**
     * @ORM\ManyToOne(targetEntity="event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $event;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;
    
    public function __toString()
    {
        return (string) $this->getTitle();
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
     * Set Created
     *
     * @param \DateTime $created
     * @ORM\PrePersist
     * @return Alert
     */
    public function setCreated()
    {

        if(!$this->created){
            $this->created = new \DateTime();
        }

        return $this;
    }
    
    /**
     * Get Created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
    
    /**
     * Set Title
     *
     * @param string $title
     *
     * @return Alert
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set Message
     *
     * @param string $message
     *
     * @return Alert
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get Message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Set URL
     *
     * @param string $url
     *
     * @return Alert
     */
    public function setURL($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get URL
     *
     * @return string
     */
    public function getURL()
    {
        return $this->url;
    }
    
    /**
     * Set Type
     *
     * @param string $type
     *
     * @return Alert
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get Type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
        /**
     * Set FoR
     *
     * @param string $for
     *
     * @return Alert
     */
    public function setFor($for)
    {
        $this->fieldOfResponsibility = $for;

        return $this;
    }

    /**
     * Get FoR
     *
     * @return string
     */
    public function getFoR()
    {
        return $this->fieldOfResponsibility;
    }
    
    
    /**
     * Set event
     *
     * @param \AppBundle\Entity\event $event
     *
     * @return Alert
     */
    public function setEvent(\AppBundle\Entity\event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \AppBundle\Entity\event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set fieldOfResponsibility
     *
     * @param string $fieldOfResponsibility
     *
     * @return Alert
     */
    public function setFieldOfResponsibility($fieldOfResponsibility)
    {
        $this->fieldOfResponsibility = $fieldOfResponsibility;

        return $this;
    }

    /**
     * Get fieldOfResponsibility
     *
     * @return string
     */
    public function getFieldOfResponsibility()
    {
        return $this->fieldOfResponsibility;
    }
}
