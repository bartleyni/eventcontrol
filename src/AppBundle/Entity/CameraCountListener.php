<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Alert;
use AppBundle\Entity\skew;
use AppBundle\Entity\camera_count;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManager;

class CameraCountListener
{
    private $slackBundle_client;
    private $slackBundle_identity_bag;
    //private $slackBundle_connection;
    //protected $em;
    protected $container;
    protected $em;

    public function __construct($client, $identity_bag, \Symfony\Component\DependencyInjection\Container $container, EntityManager $em)
    {
        $this->slackBundle_client = $client;
        $this->slackBundle_identity_bag = $identity_bag;
        //$this->slackBundle_connection = $connection;
        $this->container = $container;
        $this->em = $em;
    }
    
    public function postPersist(LifecycleEventArgs $args)
    {        
        $entity = $args->getEntity();
                
        if ($entity instanceof camera_count) {
            $this->getVenueCountCamera($entity);
        }
        if ($entity instanceof skew) {
            $this->getVenueCountSkew($entity);
        }
    }
    
    private function getVenueCountCamera(camera_count $camCount)
    {
        $venue = $camCount->getCameraId()->getVenueCamera()->getVenue();
        $name = $venue->getName();
        $venueEvents = $venue->getVenueEvent();
        foreach($venueEvents as $venueEvent){
            $venueEvent->getEvent();
            $end_timestamp = $venueEvent->getEvent()->getEventLogStopDate();
            $doors_timestamp = $venueEvent->getDoors();
            $count = $this->em->getRepository('AppBundle:Venue')->getvenuecount($venue,$end_timestamp,$doors_timestamp);
            $this->checkAndAlert($count, $name, $venueEvent);
        }
    }
    
    private function getVenueCountSkew(skew $skew)
    {
        $venue = $skew->getVenueId();
        $name = $venue->getName();
        $venueEvents = $venue->getVenueEvent();
        foreach($venueEvents as $venueEvent){
            $venueEvent->getEvent();
            $end_timestamp = $venueEvent->getEvent()->getEventLogStopDate();
            $doors_timestamp = $venueEvent->getDoors();
            $count = $this->em->getRepository('AppBundle:Venue')->getvenuecount($venue,$end_timestamp,$doors_timestamp);
            $this->checkAndAlert($count, $name, $venueEvent);
        }
    }
    
    private function checkAndAlert(int $count, string $venueName, venue_event $venueEvent)
    {
        $highAlert = $venueEvent->gethighCapacityAlert();
        $highFlag = $venueEvent->gethighCapacityFlag();
        $highHighAlert = $venueEvent->gethighHighCapacityAlert();
        $highHighFlag = $venueEvent->gethighHighCapacityFlag();
        
        if($highAlert and $highHighAlert){
            $alert = new Alert();
            $alert->setTitle('People Counting Capacity: '.$venueName);

            $alert->setURL(null);
            $alert->setFoR('People');     

            if($count >= $highHighAlert and $highHighFlag == false){
                $alert->setMessage('High High Alert, Venue Count: '.$count);
                $alert->setType("danger");
                $this->em->persist($alert);
                $this->em->flush();
                $venueEvent->sethighHighCapacityFlag(true);
                $this->em->persist($venueEvent);
                $this->em->flush();
            } else if($count >= $highAlert and $highFlag == false){
                $alert->setMessage('High Alert, Venue Count: '.$count);
                $alert->setType("warning");
                $this->em->persist($alert);
                $this->em->flush();
                $venueEvent->sethighCapacityFlag(true);
                $this->em->persist($venueEvent);
                $this->em->flush();
            } else if ($count < $highHighAlert){
                $venueEvent->sethighHighCapacityFlag(false);
                $this->em->persist($venueEvent);
                $this->em->flush();
            } else if ($count < $highAlert){
                $venueEvent->sethighHighCapacityFlag(false);
                $venueEvent->sethighCapacityFlag(false);
                $this->em->persist($venueEvent);
                $this->em->flush();
            }
        }   
    }
}
