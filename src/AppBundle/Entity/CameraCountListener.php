<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Alert;
use AppBundle\Entity\VenueCountAlerts;
use AppBundle\Entity\Queue;
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

    public function __construct($client, $identity_bag, \Symfony\Component\DependencyInjection\Container $container)
    {
        $this->slackBundle_client = $client;
        $this->slackBundle_identity_bag = $identity_bag;
        //$this->slackBundle_connection = $connection;
        $this->container = $container;
    }
    
    public function postPersist(LifecycleEventArgs $args)
    {        
        $entity = $args->getEntity();
        $this->em = $args->getEntityManager();
                
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
            $end_timestamp = $venueEvent->getEventId()->getEventLogStopDate();
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
            $end_timestamp = $venueEvent->getEventId()->getEventLogStopDate();
            $doors_timestamp = $venueEvent->getDoors();
            $count = $this->em->getRepository('AppBundle:Venue')->getvenuecount($venue,$end_timestamp,$doors_timestamp);
            $this->checkAndAlert($count, $name, $venueEvent);
        }
    }
    
    private function checkAndAlert(array $countArray, string $venueName, venue_event $venueEvent)
    {
        $countAlerts = $venueEvent->getCountAlerts();
        $count = $countArray['running_count_in']-$countArray['running_count_out'];
        $alert = new Alert();
        $alert->setTitle('People Counting Capacity Alert: '.$venueName);
        $alert->setURL(null);
        $alert->setFoR('People');  
        
        foreach($countAlerts as $countAlert){
            $direction = $countAlert->getUpDownBoth();
            $description = $countAlert->getDescription();
            $countAlertValue = $countAlert->getCount();
            $alertTriggered = $countAlert->getTriggered();
            switch ($direction)
            {
                case "UP":
                    if($count > $countAlertValue and $alertTriggered == false){
                        $alert->setMessage($description."\n Venue Count: ".$count);
                        $alert->setType("warning");
                        $this->em->persist($alert);
                        $this->em->flush();
                        $countAlert->setTriggered(true);
                        $this->em->persist($countAlert);
                        $this->em->flush();
                    } elseif($count < $countAlertValue and $alertTriggered == true) {
                        $countAlert->setTriggered(false);
                        $this->em->persist($countAlert);
                        $this->em->flush();
                    }
                    break;
                case "DOWN":
                    if($count < $countAlertValue and $triggered == false){
                        $alert->setMessage($description."\n Venue Count: ".$count);
                        $alert->setType("warning");
                        $this->em->persist($alert);
                        $this->em->flush();
                        $countAlert->setTriggered(true);
                        $this->em->persist($countAlert);
                        $this->em->flush();
                    } elseif($count > $countAlertValue and $triggered == true) {
                        $countAlert->setTriggered(false);
                        $this->em->persist($countAlert);
                        $this->em->flush();
                    }
                    break;
                case "BOTH":
                    break;
            }
        }
        
        
        
//        $highAlert = $venueEvent->gethighCapacityAlert();
//        $highFlag = $venueEvent->gethighCapacityFlag();
//        $highHighAlert = $venueEvent->gethighHighCapacityAlert();
//        $highHighFlag = $venueEvent->gethighHighCapacityFlag();
//        
//        if($highAlert and $highHighAlert){
//            $alert = new Alert();
//            $alert->setTitle('People Counting Capacity: '.$venueName);
//
//            $alert->setURL(null);
//            $alert->setFoR('People');     
//
//            if($count >= $highHighAlert and $highHighFlag == false){
//                $alert->setMessage('High High Alert, Venue Count: '.$count);
//                $alert->setType("danger");
//                $this->em->persist($alert);
//                $this->em->flush();
//                //Moved to alert listener
//                //$alert_queue = new Queue();
//                //$alert_queue->setAlert($alert);                  
//                //$em->persist($alert_queue);
//                //$em->flush();
//                $venueEvent->sethighHighCapacityFlag(true);
//                $this->em->persist($venueEvent);
//                $this->em->flush();
//            } else if($count >= $highAlert and $highFlag == false){
//                $alert->setMessage('High Alert, Venue Count: '.$count);
//                $alert->setType("warning");
//                $this->em->persist($alert);
//                $this->em->flush();
//                //$alert_queue = new Queue();
//                //$alert_queue->setAlert($alert);                  
//                //$this->em->persist($alert_queue);
//                //$this->em->flush();
//                $venueEvent->sethighCapacityFlag(true);
//                $this->em->persist($venueEvent);
//                $this->em->flush();
//            } else if ($count < $highAlert){
//                $venueEvent->sethighHighCapacityFlag(false);
//                $venueEvent->sethighCapacityFlag(false);
//                $this->em->persist($venueEvent);
//                $this->em->flush();
//            } else if ($count < $highHighAlert){
//                $venueEvent->sethighHighCapacityFlag(false);
//                $this->em->persist($venueEvent);
//                $this->em->flush();
//            }
//            
//            
//        }   
    }
}
