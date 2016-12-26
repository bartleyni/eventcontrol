<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="logFiles")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class logFile
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
         
         
    /**
     * Image path
     *
     * @var string
     *
     * @ORM\Column(type="text", length=255, nullable=false)
     */
    protected $path;

    /**
     * Image file
     *
     * @var File
     * @Assert\File(
     *     maxSize = "50M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/tiff", "application/pdf"},
     *     maxSizeMessage = "The maxmimum allowed file size is 50MB.",
     *     mimeTypesMessage = "Only the filetypes are allowed."
     * )
     */
    protected $file;

    /**
     * @ORM\ManyToOne(targetEntity="log_entries")
     * @ORM\JoinColumn(name="log_id", referencedColumnName="id")
     */
    
    private $log_entry;
    
    public function __toString()
    {
        return (string) $this->getFile();
    }
    
    public function getFile()
    {
        return $this->file;
    }
    
    /**
    * Called before saving the entity
    * 
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */
   public function preUpload()
   {   
       if (null !== $this->file) {
           // do whatever you want to generate a unique name
           $filename = sha1(uniqid(mt_rand(), true));
           $this->path = $filename.'.'.$this->file->guessExtension();
       }
    }
    
    /**
    * Called before entity removal
    *
    * @ORM\PreRemove()
    */
   public function removeUpload()
   {
       if ($file = $this->getAbsolutePath()) {
           unlink($file); 
       }
    }
    
    /**
    * Called after entity persistence
    *
    * @ORM\PostPersist()
    * @ORM\PostUpdate()
    */
   public function upload()
   {
       if (null === $this->file) {
           return;
       }
       $this->file->move(
           $this->getUploadRootDir(),
           $this->path
       );

       $this->file = null;
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
     * Set path
     *
     * @param string $path
     *
     * @return logFile
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set logEntry
     *
     * @param \AppBundle\Entity\log_entries $logEntry
     *
     * @return logFile
     */
    public function setLogEntry(\AppBundle\Entity\log_entries $logEntry = null)
    {
        $this->log_entry = $logEntry;

        return $this;
    }

    /**
     * Get logEntry
     *
     * @return \AppBundle\Entity\log_entries
     */
    public function getLogEntry()
    {
        return $this->log_entry;
    }
}
