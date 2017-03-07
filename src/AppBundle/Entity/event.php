<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of event
 *
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Xiidea\EasyAuditBundle\Annotation\ORMSubscribedEvents;

/**
 * @ORM\Entity
 * @ORM\Table(name="event")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 * @ORMSubscribedEvents()
 */

class event {

    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;
 
    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $client;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $event_date;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $event_log_start_date;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $event_log_stop_date;
    
    /**
     * @ORM\OneToMany(targetEntity="log_entries", mappedBy="event")
     */
    private $log_entries;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $event_report_run_date;
    
    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $event_report_filename;
    
    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $event_lat_long;
    
    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    protected $event_last_weather;
    
    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    protected $event_last_weather_warning;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $event_last_weather_warning_update;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $event_last_weather_update;
    
    /**
     * @ORM\ManyToMany(targetEntity="UPS", inversedBy="event")
     *
     */
    protected $UPSs;
    
    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="event")
     *
     */
    protected $Users;

    /**
     * @ORM\OneToMany(targetEntity="venue_event", mappedBy="event_id")
     */
    protected $venue_event;
    
    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $northEastBounds_lat_long;
    
    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $southWestBounds_lat_long;
    
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="event_overlay", fileNameProperty="overlay_imageName")
     * 
     * @var File
     */
    private $overlay_imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $overlay_imageName;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $overlay_updatedAt;
    
    /**
    * @ORM\OneToMany(targetEntity="Locations", mappedBy="event", cascade={"persist"})
    * @Assert\Valid
    * @Assert\Collection(
    *     fields = {
    *         "locationText" = {
    *             @Assert\NotBlank(),
    *             @Assert\Length(
    *                 min = 3,
    *                 minMessage = "Location does not have enough characters"
    *             )
    *         },
    *         "locationLatLong" = {
    *             @Assert\NotBlank(),
    *             @Assert\Length(
    *                 min = 6,
    *                 minMessage = "Lat Long does not have enough characters"
    *             )
    *         }
    *     },
    * )
    */
    protected $locations;
    
    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="selected_event")
     */
    private $user_selected;
    
    public function __toString()
    {
        return (string) $this->getName();
    }
    
    public function __construct()
    {
        $this->UPSs = new ArrayCollection();
        $this->locations = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return event
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set client
     *
     * @param string $client
     *
     * @return event
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return string
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set eventDate
     *
     * @param \DateTime $eventDate
     *
     * @return event
     */
    public function setEventDate($eventDate)
    {
        $this->event_date = $eventDate;

        return $this;
    }

    /**
     * Get eventDate
     *
     * @return \DateTime
     */
    public function getEventDate()
    {
        return $this->event_date;
    }

    /**
     * Set eventLogStartDate
     *
     * @param \DateTime $eventLogStartDate
     *
     * @return event
     */
    public function setEventLogStartDate($eventLogStartDate)
    {
        $this->event_log_start_date = $eventLogStartDate;

        return $this;
    }

    /**
     * Get eventLogStartDate
     *
     * @return \DateTime
     */
    public function getEventLogStartDate()
    {
        return $this->event_log_start_date;
    }

    /**
     * Set eventLogStopDate
     *
     * @param \DateTime $eventLogStopDate
     *
     * @return event
     */
    public function setEventLogStopDate($eventLogStopDate)
    {
        $this->event_log_stop_date = $eventLogStopDate;

        return $this;
    }

    /**
     * Get eventLogStopDate
     *
     * @return \DateTime
     */
    public function getEventLogStopDate()
    {
        return $this->event_log_stop_date;
    }

    /**
     * Set eventReportRunDate
     *
     * @param \DateTime $eventReportRunDate
     *
     * @return event
     */
    public function setEventReportRunDate($eventReportRunDate)
    {
        $this->event_report_run_date = $eventReportRunDate;

        return $this;
    }

    /**
     * Get eventReportRunDate
     *
     * @return \DateTime
     */
    public function getEventReportRunDate()
    {
        return $this->event_report_run_date;
    }
    
    /**
     * Set event_report_filename
     *
     * @param string $event_report_filename
     *
     * @return event
     */
    public function setEventReportFilename($event_report_filename)
    {
        $this->event_report_filename = $event_report_filename;

        return $this;
    }

    /**
     * Get event_report_filename
     *
     * @return string
     */
    public function getEventReportFilename()
    {
        return $this->event_report_filename;
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
    
    /**
     * Set event_lat_long
     *
     * @param string $event_lat_long
     *
     * @return event
     */
    public function setEventLatLong($event_lat_long)
    {
        $this->event_lat_long = $event_lat_long;

        return $this;
    }

    /**
     * Get event_lat_long
     *
     * @return string
     */
    public function getEventLatLong()
    {
        return $this->event_lat_long;
    }
    
    /**
     * Set event_last_weather
     *
     * @param string $event_last_weather
     *
     * @return event
     */
    public function setEventLastWeather($event_last_weather)
    {
        $this->event_last_weather = $event_last_weather;

        return $this;
    }
    
    /**
     * Get event_last_weather
     *
     * @return string
     */
    public function getEventLastWeather()
    {
        return $this->event_last_weather;
    }
    
        /**
     * Set event_last_weather_warning
     *
     * @param string $event_last_weather_warning
     *
     * @return event
     */
    public function setEventLastWeatherWarning($event_last_weather_warning)
    {
        $this->event_last_weather_warning = $event_last_weather_warning;

        return $this;
    }
    
    /**
     * Get event_last_weather_warning
     *
     * @return string
     */
    public function getEventLastWeatherWarning()
    {
        return $this->event_last_weather_warning;
    }
    
    /**
     * Set eventLastWeatherUpdate
     *
     * @param \DateTime $eventLastWeatherUpdate
     *
     * @return event
     */
    public function setEventLastWeatherUpdate($eventLastWeatherUpdate)
    {
        $this->event_last_weather_update = $eventLastWeatherUpdate;

        return $this;
    }

    /**
     * Get eventLastWeatherUpdate
     *
     * @return \DateTime
     */
    public function getEventLastWeatherUpdate()
    {
        return $this->event_last_weather_update;
    }
    
    /**
     * Set eventLastWeatherWarningUpdate
     *
     * @param \DateTime $eventLastWeatherWarningUpdate
     *
     * @return event
     */
    public function setEventLastWeatherWarningUpdate($eventLastWeatherWarningUpdate)
    {
        $this->event_last_weather_warning_update = $eventLastWeatherWarningUpdate;

        return $this;
    }

    /**
     * Get eventLastWeatherWarningUpdate
     *
     * @return \DateTime
     */
    public function getEventLastWeatherWarningUpdate()
    {
        return $this->event_last_weather_warning_update;
    }
    
    public function getUPSs()
    {
        return $this->UPSs;
    }

    public function getVenues()
    {
        return $this->venues;
    }
    
    /**
     * Get northEastBounds_lat_long
     *
     * @return string
     */
    public function getNorthEastBounds()
    {
        return $this->northEastBounds_lat_long;
    }
    
    /**
     * Set northEastBounds_lat_long
     *
     * @param string $northEastBounds_lat_long
     *
     * @return event
     */
    public function setNorthEastBounds($northEastBounds_lat_long)
    {
        $this->northEastBounds_lat_long = $northEastBounds_lat_long;

        return $this;
    }
    
    /**
     * Get southWestBounds_lat_long
     *
     * @return string
     */
    public function getSouthWestBounds()
    {
        return $this->southWestBounds_lat_long;
    }
    
    /**
     * Set southWestBounds_lat_long
     *
     * @param string $southWestBounds_lat_long
     *
     * @return event
     */
    public function setSouthWestBounds($southWestBounds_lat_long)
    {
        $this->southWestBounds_lat_long = $southWestBounds_lat_long;

        return $this;
    }
    
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return event
     */
    public function setOverlayImageFile(File $image = null)
    {
        $this->overlay_imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->overlay_updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getOverlayImageFile()
    {
        return $this->overlay_imageFile;
    }

    /**
     * @param string $imageName
     *
     * @return event
     */
    public function setOverlayImageName($imageName)
    {
        $this->overlay_imageName = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOverlayImageName()
    {
        return $this->overlay_imageName;
    }
    
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * Set northEastBoundsLatLong
     *
     * @param string $northEastBoundsLatLong
     *
     * @return event
     */
    public function setNorthEastBoundsLatLong($northEastBoundsLatLong)
    {
        $this->northEastBounds_lat_long = $northEastBoundsLatLong;

        return $this;
    }

    /**
     * Get northEastBoundsLatLong
     *
     * @return string
     */
    public function getNorthEastBoundsLatLong()
    {
        return $this->northEastBounds_lat_long;
    }

    /**
     * Set southWestBoundsLatLong
     *
     * @param string $southWestBoundsLatLong
     *
     * @return event
     */
    public function setSouthWestBoundsLatLong($southWestBoundsLatLong)
    {
        $this->southWestBounds_lat_long = $southWestBoundsLatLong;

        return $this;
    }

    /**
     * Get southWestBoundsLatLong
     *
     * @return string
     */
    public function getSouthWestBoundsLatLong()
    {
        return $this->southWestBounds_lat_long;
    }

    /**
     * Set overlayUpdatedAt
     *
     * @param \DateTime $overlayUpdatedAt
     *
     * @return event
     */
    public function setOverlayUpdatedAt($overlayUpdatedAt)
    {
        $this->overlay_updatedAt = $overlayUpdatedAt;

        return $this;
    }

    /**
     * Get overlayUpdatedAt
     *
     * @return \DateTime
     */
    public function getOverlayUpdatedAt()
    {
        return $this->overlay_updatedAt;
    }

    /**
     * Add logEntry
     *
     * @param \AppBundle\Entity\log_entries $logEntry
     *
     * @return event
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
     * Add uPSs
     *
     * @param \AppBundle\Entity\UPS $uPSs
     *
     * @return event
     */
    public function addUPSs(\AppBundle\Entity\UPS $uPSs)
    {
        $this->UPSs[] = $uPSs;

        return $this;
    }

    /**
     * Remove uPSs
     *
     * @param \AppBundle\Entity\UPS $uPSs
     */
    public function removeUPSs(\AppBundle\Entity\UPS $uPSs)
    {
        $this->UPSs->removeElement($uPSs);
    }
    /**
     * Add Venue
     *
     * @param \AppBundle\Entity\venue $venue
     *
     * @return event
     */

    public function addVenues(\AppBundle\Entity\venue $venue)
    {
        $this->venues[] = $venue;

        return $this;
    }

    /**
     * Remove venue
     *
     * @param \AppBundle\Entity\venue $venue
     */
    public function removeVenues(\AppBundle\Entity\venue $venue)
    {
        $this->venues->removeElement($venue);
    }


    /**
     * Add location
     *
     * @param \AppBundle\Entity\Locations $location
     *
     * @return event
     */
    public function addLocation(\AppBundle\Entity\Locations $location)
    {
        //$this->locations[] = $location;
        $location->setEvent($this);
        //$this->locations->add($location);

        return $this;
    }

    /**
     * Remove location
     *
     * @param \AppBundle\Entity\Locations $location
     */
    public function removeLocation(\AppBundle\Entity\Locations $location)
    {
        $this->locations->removeElement($location);
    }

    /**
     * Add venue
     *
     * @param \AppBundle\Entity\venue $venue
     *
     * @return event
     */
    public function addVenue(\AppBundle\Entity\venue $venue)
    {
        $this->venues[] = $venue;

        return $this;
    }

    /**
     * Remove venue
     *
     * @param \AppBundle\Entity\venue $venue
     */
    public function removeVenue(\AppBundle\Entity\venue $venue)
    {
        $this->venues->removeElement($venue);
    }

    /**
     * Add userSelected
     *
     * @param \AppBundle\Entity\User $userSelected
     *
     * @return event
     */
    public function addUserSelected(\AppBundle\Entity\User $userSelected)
    {
        $this->user_selected[] = $userSelected;

        return $this;
    }

    /**
     * Remove userSelected
     *
     * @param \AppBundle\Entity\User $userSelected
     */
    public function removeUserSelected(\AppBundle\Entity\User $userSelected)
    {
        $this->user_selected->removeElement($userSelected);
    }

    /**
     * Get userSelected
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserSelected()
    {
        return $this->user_selected;
    }
}
