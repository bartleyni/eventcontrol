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
use AppBundle\Entity\Alert;
use AppBundle\Entity\History;
use AppBundle\Entity\Queue;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Description of AlertController
 *
 * @author Nick
 */
class AlertController extends Controller
{
    /**
     * @Route("/Alert/Queue/", name="Alert_Queue");
     * 
     */
    public function AlertQueueAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $usr = $this->get('security.context')->getToken()->getUser();
        $operatorId = $usr->getId();
        
        $event = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        
        $Queue = $em->getRepository('AppBundle\Entity\Queue')->findBy(
                    array('event' => $event));
        if ($Queue)
        {
                $response = new JsonResponse();
                $response->setData($Queue);

        } else {
            $response->setContent('Hello World');
            $response->headers->set('Content-Type', 'text/plain');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        
        return $response;
    }
    
    /**
     * @Route("/Alert/Dismiss/{id}", name="Alert_Dismiss");
     * 
     */
    public function AlertDismissAction($id = null)
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
        
        $response = new Response('UPS updated',Response::HTTP_OK, array('content-type' => 'text/html'));

        return $response;
    }
    
    /**
     * @Route("/Alert/Acknowledge/{id}", name="Alert_Ack");
     * 
     */
    public function AlertAckAction($id = null)
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
        
        $response = new Response('UPS updated',Response::HTTP_OK, array('content-type' => 'text/html'));

        return $response;
    }
    
}
