<?php
namespace AppBundle\Entity;
use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class venueRepository extends EntityRepository
{

    public function getactiveeventvenues($usr)
    {
        $operatorId = $usr->getId();
        $active_event = $usr->getSelectedEvent();

        $query = $this->getEntityManager()
            ->createQuery('SELECT v, e, ve FROM AppBundle\Entity\venue_event ve
            JOIN ve.event_id e
            JOIN ve.venue_id v
            WHERE ve.event_id = :id'
            )->setParameter('id', $active_event);

        $output =  $query->getArrayResult();
       
        return $output;
    }
    
    public function getEventVenues($eventId)
    {
        $em = $this->getEntityManager();
        $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(array('id' => $eventId));

        $query = $this->getEntityManager()
            ->createQuery('SELECT v, e, ve FROM AppBundle\Entity\venue_event ve
            JOIN ve.event_id e
            JOIN ve.venue_id v
            WHERE ve.event_id = :id'
            )->setParameter('id', $event);

        $output =  $query->getArrayResult();
       
        return $output;
    }
    
        public function getEventsVenuesByEventId($eventId)
    {
        $em = $this->getEntityManager();
        $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(array('id' => $eventId));

        $query = $this->getEntityManager()
            ->createQuery('SELECT v.id, v.name, ve.doors, e, ve FROM AppBundle\Entity\venue_event ve
            JOIN ve.event_id e
            JOIN ve.venue_id v
            WHERE ve.event_id = :id'
            )->setParameter('id', $event);

        $output =  $query->getArrayResult();
       
        return $output;
    }
    
    public function getvenuedoors($id, $event)
    {
        //return "hi";
        $doors = $this->getEntityManager()->createQuery('SELECT p.doors FROM AppBundle\Entity\venue_event p  WHERE p.venue_id = :venue_id AND p.event_id = :event_id')->setParameter('event_id', $event)->setParameter('venue_id', $id)->getOneOrNullResult();
        return $doors['doors'];
               
    }
 
    public function getvenuestatus($id)
    {
        $cameras = $this->getEntityManager()->getRepository('AppBundle\Entity\venue_camera')->getvenuecameras($id);
        $output = true;
        foreach ($cameras as $camera) {
            $status =$this->getEntityManager()->getRepository('AppBundle\Entity\camera_count')->iscamerauptodate($camera['camera_id']);
            if(!$status){
                $output = false;
            }
        }
        return $output;
    }
    public function getpeoplecountingstatus()
    {
        $venues = $this->getEntityManager()->createQuery('SELECT p.id FROM AppBundle\Entity\venue p')->getResult();

        $output = true;
        foreach ($venues as $venue) {
            $status=$this->getEntityManager()->getRepository('AppBundle\Entity\venue')->getvenuestatus($venue['id']);
            if(!$status){
                $output = false;
            }
        }
        return $output;
    }

    public function getvenuecount($id, $endtimestamp, $timestamp)
    {
        $cameras = $this->getEntityManager()->getRepository('AppBundle\Entity\venue_camera')->getvenuecameras($id);
        $output = array();
        $output['running_count_in'] = 0;
        $output['running_count_out'] = 0;
        $skews = $this->getEntityManager()->getRepository('AppBundle\Entity\skew')->getvenueskew($id, $timestamp);
        foreach ($cameras as $camera) {
            if ($camera['inverse']) {
                $camera_doors = $this->getEntityManager()->getRepository('AppBundle\Entity\camera_count')->getcameradoors($camera['camera_id'], $timestamp);
                $camera_count = $this->getEntityManager()->getRepository('AppBundle\Entity\camera_count')->getcameracount($camera['camera_id'], $endtimestamp);
                $output['running_count_in'] += $camera_count['running_count_out'] - $camera_doors['running_count_out'];
                $output['running_count_out'] += $camera_count['running_count_in'] - $camera_doors['running_count_in'];
            } else {
                $camera_doors = $this->getEntityManager()->getRepository('AppBundle\Entity\camera_count')->getcameradoors($camera['camera_id'], $timestamp);
                $camera_count = $this->getEntityManager()->getRepository('AppBundle\Entity\camera_count')->getcameracount($camera['camera_id'], $endtimestamp);
                //echo "print camre doors";
                //print_r($camera_doors);
                //echo "print camera count";
                //print_r($camera_count);
                $output['running_count_in'] += $camera_count['running_count_in'] - $camera_doors['running_count_in'];
                $output['running_count_out'] += $camera_count['running_count_out'] - $camera_doors['running_count_out'];
            }
            
        }

        foreach ($skews as $skew) {
            $output['running_count_in'] += $skew['skew_in'];
            $output['running_count_out'] += $skew['skew_out'];
        }
        return $output;
    }

    public function getvenuedetailedcount($id, $endtimestamp, $timestamp)
    {
        $cameras = $this->getEntityManager()->getRepository('AppBundle\Entity\venue_camera')->getvenuecameras($id);
        $skews = $this->getEntityManager()->getRepository('AppBundle\Entity\skew')->getvenueskew($id, $timestamp);
        $output['totals']['running_count_in'] = 0;
        $output['totals']['running_count_out'] = 0;
        foreach ($cameras as $key => $camera) {
            if ($camera['inverse']) {
                $camera_doors = $this->getEntityManager()->getRepository('AppBundle\Entity\camera_count')->getcameradoors($camera['camera_id'], $timestamp);
                $camera_count = $this->getEntityManager()->getRepository('AppBundle\Entity\camera_count')->getcameracount($camera['camera_id'], $endtimestamp);
                $cameras[$key]['count_in'] = ($camera_count['running_count_out'] - $camera_doors['running_count_out']);
                $cameras[$key]['count_out'] = ($camera_count['running_count_in'] - $camera_doors['running_count_in']);
                $output['totals']['running_count_in'] += ($camera_count['running_count_out'] - $camera_doors['running_count_out']);
                $output['totals']['running_count_out'] += ($camera_count['running_count_in'] - $camera_doors['running_count_in']);
            } else {
                $camera_doors = $this->getEntityManager()->getRepository('AppBundle\Entity\camera_count')->getcameradoors($camera['camera_id'], $timestamp);
                $camera_count = $this->getEntityManager()->getRepository('AppBundle\Entity\camera_count')->getcameracount($camera['camera_id'], $endtimestamp);
                $cameras[$key]['count_in'] = ($camera_count['running_count_in'] - $camera_doors['running_count_in']);
                $cameras[$key]['count_out'] = ($camera_count['running_count_out'] - $camera_doors['running_count_out']);
                $output['totals']['running_count_in'] += ($camera_count['running_count_in'] - $camera_doors['running_count_in']);
                $output['totals']['running_count_out'] += ($camera_count['running_count_out'] - $camera_doors['running_count_out']);
            }
            $status =$this->getEntityManager()->getRepository('AppBundle\Entity\camera_count')->iscamerauptodate($camera['camera_id']);
            if ($status) {   $cameras[$key]['status'] = "true"; }else{  $cameras[$key]['status'] = "false"; }
        }
        $output['skew']['count_in'] = 0;
        $output['skew']['count_out'] = 0;
        foreach ($skews as $skew) {
            $output['skew']['count_in'] += $skew['skew_in'];
            $output['skew']['count_out'] += $skew['skew_out'];
            $output['totals']['running_count_in'] += $skew['skew_in'];
            $output['totals']['running_count_out'] += $skew['skew_out'];
        }
        $output['cameras'] = $cameras;
        return $output;
    }
    
}
