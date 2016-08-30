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
     * @ORM\Id
     * @Assert\File(
     *     maxSize = "50M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/tiff", "application/pdf"},
     *     maxSizeMessage = "The maxmimum allowed file size is 50MB.",
     *     mimeTypesMessage = "Only the filetypes are allowed."
     * )
     */
    protected $file;

    /**
     * @ORM\ManyToOne(targetEntity="log_entries", inversedBy="log_files")
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
}
