<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of USer
 *
 * @author Nick
 */
 
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Xiidea\EasyAuditBundle\Annotation\ORMSubscribedEvents;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 * @ORMSubscribedEvents()
 */

//class User implements AdvancedUserInterface, \Serializable {
class User implements UserInterface, \Serializable {
/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(groups={"registration", "update"})
     */
    private $username;

    /**
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Length(max=4096)
     */
    private $plainPassword;
    
    /**
     * @SecurityAssert\UserPassword(
     *     message = "Wrong value for your current password"
     * )
     */
    private $oldPassword;
    
    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(groups={"registration", "update"})
     * @Assert\Email()
     */
    private $email;
        
    /**
     * @ORM\Column(type="string", length=255, unique=false)
     * @Assert\NotBlank(groups={"registration", "update"})
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    private $firebaseID;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;
    
    /**
     * @ORM\OneToMany(targetEntity="log_entries", mappedBy="operator")
     */
    private $log_entries;
    
    /**
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
     *
     */
    protected $groups;
    
    /**
     * @ORM\ManyToOne(targetEntity="event", inversedBy="user_selected")
     * @ORM\JoinColumn(name="selected_event", referencedColumnName="id", nullable=true)
     */
    private $selected_event;
    
     /**
     * @ORM\ManyToMany(targetEntity="event", mappedBy="Users")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $events;
    
    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $apiToken;
    
    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
        $this->groups = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getapitoken()
    {
        return $this->apiToken;
    }

    public function setapitoken($apiToken)
    {
        $this->apiToken = $apiToken;
    }
    
        public function getFirebaseID()
    {
        return $this->firebaseID;
    }

    public function setFirebaseID($firebaseID)
    {
        $this->firebaseID = $firebaseID;
    }
    
    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function __toString()
    {
        return (string) $this->getName();
    }
    
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
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
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Add logEntry
     *
     * @param \AppBundle\Entity\log_entries $logEntry
     *
     * @return User
     */
    public function addLogEntry(\AppBundle\Entity\log_entries $logEntry)
    {
        $this->log_entries[] = $logEntry;

        return $this;
    }

    /**
     * Remove logEntry
     *
     * @param \AppBundle\Entity\log_entries $logEntry
     */
    public function removeLogEntry(\AppBundle\Entity\log_entries $logEntry)
    {
        $this->log_entries->removeElement($logEntry);
    }

    /**
     * Get logEntries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLogEntries()
    {
        return $this->log_entries;
    }
    
    public function getRoles()
    {
        return $this->groups->toArray();
    }
    
    public function getGroups()
    {
        return $this->groups;
    }
    
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * Add group
     *
     * @param \AppBundle\Entity\Group $group
     *
     * @return User
     */
    public function addGroup(\AppBundle\Entity\Group $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove group
     *
     * @param \AppBundle\Entity\Group $group
     */
    public function removeGroup(\AppBundle\Entity\Group $group)
    {
        $this->groups->removeElement($group);
    }
    
     /**
     * Set selected_event
     *
     * @param \AppBundle\Entity\event $event
     *
     * @return User
     */
    public function setSelectedEvent(\AppBundle\Entity\event $event = null)
    {
        $this->selected_event = $event;

        return $this;
    }

    /**
     * Get selected_event
     *
     * @return \AppBundle\Entity\event
     */
    public function getSelectedEvent()
    {
        return $this->selected_event;
    }
 
     /**
     * Add events
     *
     * @param \AppBundle\Entity\event $event
     *
     * @return User
     */
    public function addEvents(\AppBundle\Entity\event $event)
    {
        $this->events[] = $event;
        return $this;
    }
    /**
     * Remove events
     *
     * @param \AppBundle\Entity\event $event
     */
    public function removeEvents(\AppBundle\Entity\event $event)
    {
        $this->events->removeElement($event);
    }
 
     /**
     * Set events
     *
     * @param \AppBundle\Entity\event $event
     *
     * @return events
     */
    public function setEvents(\AppBundle\Entity\event $event = null)
    {
        $this->events = $event;
        return $this;
    }
    /**
     * Get events
     *
     * @return \AppBundle\Entity\event
     */
    public function getEvents()
    {
        return $this->events;
    }
}
