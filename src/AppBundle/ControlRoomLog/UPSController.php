<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\UPS;
use AppBundle\Entity\UPS_Status;
use AppBundle\Entity\Alert;
use AppBundle\Entity\Queue;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Description of UPSController
 *
 * @author Nick
 */
class UPSController extends Controller
{
    /**
     * @Route("/UPS/status/{event_id}", name="UPS_status");
     * 
     */
    public function UPSAction(Request $request, $event_id = null)
    {
        $em = $this->getDoctrine()->getManager();
        
        //$event = $em->getRepository('AppBundle\Entity\event')->findOneBy(array('id' => $event_id));
        
        $ups_statuses = $em->getRepository('AppBundle\Entity\UPS_Status')->getLatestUPS($event_id);
        
        if ($ups_statuses)
        {
                $response = new JsonResponse();
                $response->setData($ups_statuses);

        } else {
            $response->setContent('Hello World');
            $response->headers->set('Content-Type', 'text/plain');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        
        return $response;
    }
    
    /**
     * @Route("/UPS/update/{id}/{status}/", name="UPS_update");
     * 
     */
    public function UPSUpdateAction($id, $status)
    {
        $em = $this->getDoctrine()->getManager();
        
        $ups = $em->getRepository('AppBundle\Entity\UPS')->find($id);
        
        $em->flush();
        
        $UPSstatus = new UPS_Status();
        
        $UPSstatus->setUPS($ups);
        $UPSstatus->setStatus($status);
        $UPSstatus->setLineVoltage(NULL);
        $UPSstatus->setLoadPercentage(NULL);
        $UPSstatus->setBatteryVoltage(NULL); 
        $UPSstatus->setTimeLeft(NULL);
        $UPSstatus->setTimestamp();
        
        $em->persist($UPSstatus);
        $em->flush();
        
        if($status!="Mains"){
            $alert = new Alert();
            $alert->setTitle($ups.' '.$status);
            $alert->setMessage('UPS: '.$ups.'<br>Status: '.$status.'<br>Location: '.$ups->getLocation());
            $alert->setURL(null);
            if($status=="Fault"){
                $alert->setType("danger");
            } else {
                $alert->setType("warning");
            }
            //$alert->setEvent($ups->getEvent());
            $em->persist($alert);
            $em->flush();
            
            $alert_queue = new Queue();
            $alert_queue->setAlert($alert);                  
            $em->persist($alert_queue);
            $em->flush();
        }
        $response = new Response('UPS updated',Response::HTTP_OK, array('content-type' => 'text/html'));

        return $response;
    }
    
    /**
     * @Route("/UPS/update/{id}/{status}/{line}/{load}/{battery}/{time}", name="UPS_detailed_update");
     * 
     */
    public function UPSDetailedUpdateAction($id, $status, $line, $load, $battery, $time)
    {
        $em = $this->getDoctrine()->getManager();
        
        $ups = $em->getRepository('AppBundle\Entity\UPS')->find($id);
        
        $em->flush();
        
        $UPSstatus = new UPS_Status();
        
        $UPSstatus->setUPS($ups);
        $UPSstatus->setStatus($status);
        $UPSstatus->setLineVoltage($line);
        $UPSstatus->setLoadPercentage($load);
        $UPSstatus->setBatteryVoltage($battery);
        $UPSstatus->setTimeLeft($time);
        $UPSstatus->setTimestamp();
        
        $em->persist($UPSstatus);
        $em->flush();
        
        if($status!="Mains"){
            $alert = new Alert();
            $alert->setTitle($ups.' '.$status);
            $alert->setMessage('UPS: '.$ups.'<br>Status: '.$status.'<br>Location: '.$ups->getLocation().'<br>Line Voltage: '.$line.' Volts AC<br>Load: '.$load.'%<br>Battery Voltage: '.$battery.' Volts DC<br>Time Remaining: '.$time.'minutes');
            $alert->setURL(null);
            $alert->setType("danger");
            //$alert->setEvent($ups->getEvent());
            $em->persist($alert);
            $em->flush();
            
            $alert_queue = new Queue();
            $alert_queue->setAlert($alert);                  
            $em->persist($alert_queue);
            $em->flush();
        }
        
        $response = new Response('UPS updated',Response::HTTP_OK, array('content-type' => 'text/html'));

        return $response;
    }
    
}
