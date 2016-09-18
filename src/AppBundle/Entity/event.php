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

/**
 * @ORM\Entity
 * @ORM\Table(name="event")
 */

class event {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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
    
    public function __toString()
    {
        return (string) $this->getName();
    }
    
    public function __construct()
    {
        $this->UPSs = new ArrayCollection();
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
    
}
