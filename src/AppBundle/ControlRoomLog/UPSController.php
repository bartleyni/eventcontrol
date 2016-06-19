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
     * @Route("/UPS/status", name="UPS_status");
     * 
     */
    public function UPSAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        //$query = $em->createQuery('SELECT status1.id, status1.status, status1.timestamp, ups.name, ups.location, ups.power, ups.id FROM AppBundle\Entity\UPS_Status status1 JOIN status1.UPS ups WHERE status1.timestamp=(SELECT MAX(status2.timestamp) FROM AppBundle\Entity\UPS_Status status2 WHERE status1.UPS=status2.UPS)');
        
        //$ups_statuses = $query->getResult();
        
        $now = new \DateTime();
        
        $ups_statuses = $em->getRepository('AppBundle\Entity\UPS_Status')->getLatestUPS();
        
        foreach ($ups_statuses as $status)
        {
            //$interval1 = date_diff($status.timestamp, $now);
            //$interval2 = intval($interval1->format('%i'));
            //if ($interval2 > 15)
            //{
                $status->setStatus('Timeout');
            //}
        }
        
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
     * @Route("/UPS/update/{id}/{status}", name="UPS_update");
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
        $UPSstatus->setTimestamp();
        
        $em->persist($UPSstatus);
        $em->flush();
        
        $response = new Response('UPS updated',Response::HTTP_OK, array('content-type' => 'text/html'));

        return $response;
    }
}
