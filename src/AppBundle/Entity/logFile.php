<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Table(name="logFiles")
 * @ORM\Entity()
 * @Vich\Uploadable
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
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @var string
     */
    private $fileName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;
    
    /**
     * Image file
     *
     * @Vich\UploadableField(mapping="log_support_file", fileNameProperty="fileName")
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
     * @ORM\ManyToOne(targetEntity="log_entries", inversedBy="logFiles")
     * @ORM\JoinColumn(name="log_id", referencedColumnName="id", nullable=true)
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
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return event
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;

        if ($file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
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
           //$filename = sha1(uniqid(mt_rand(), true));
           $filename = $this->fileName;
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
       if ($file == $this->path) {
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
       //$this->file->move(
       //    $this->getUploadRootDir(),
       //    $this->path
       //);

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
     * set File Name
     * @param string $fileName
     *
     * @return logfile
     */
    public function setfileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get File Name
     *
     * @return string|null
     */
    public function getfileName()
    {
        return $this->fileName;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return logFile
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
