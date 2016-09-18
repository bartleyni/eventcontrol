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
        $em->flush();
        
        $qb = $em->createQueryBuilder(); 
        
        $qb
            ->select('queue.id, (queue.Alert), Alert.title, Alert.message, Alert.url, Alert.type, (Alert.event), Alert.created')
            ->from('AppBundle\Entity\Queue', 'queue')
            ->leftJoin('AppBundle\Entity\Alert', 'Alert', 'WITH', 'Alert = queue.Alert')
            ->leftJoin('AppBundle\Entity\Event', 'Event', 'WITH', 'Event = Alert.event')
            ->where('(Alert.event) = :event')
            ->orWhere('Alert.event IS NULL')
            ->setParameter('event', $event)
            ;
        
        $query = $qb->getQuery();
        $Queue = $query->getResult();
        
        if ($Queue)
        {
                $response = new JsonResponse();
                $response->setData($Queue);
        } else {
            
            $response = new JsonResponse();
            $response->setData(null);
//            $response = new Response();
//            $response->setContent('None');
//            $response->headers->set('Content-Type', 'text/plain');
//            $response->setStatusCode(Response::HTTP_NOT_FOUND);
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
        
        $alert_queue = $em->getRepository('AppBundle\Entity\Queue')->findOneBy((array('id' => $id)));
        $alert_queue->setViewed();
        $em->persist($alert_queue);
        $em->flush();
        
        $response = new Response('Alert Dimissed',Response::HTTP_OK, array('content-type' => 'text/html'));

        return $response;
    }
    
    /**
     * @Route("/Alert/Acknowledge/{id}", name="Alert_Ack");
     * 
     */
    public function AlertAckAction($id = null)
    {   
        
        $usr = $this->get('security.context')->getToken()->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        $response = new Response('Alert Acknowledgement Failed',Response::HTTP_OK, array('content-type' => 'text/html'));
        
        if($id){
            $alert_queue = $em->getRepository('AppBundle\Entity\Queue')->findOneBy((array('id' => $id)));
            if($alert_queue)
            {
                $alert = $alert_queue->getAlert();
                $em->remove($alert_queue);
                $em->flush();

                $alert_history = new History();
                $alert_history->setAlert($alert);
                $alert_history->setOperator($usr);

                $em->persist($alert_history);
                $em->flush();

                $response = new Response('Alert Acknowledged',Response::HTTP_OK, array('content-type' => 'text/html'));
            }
        }
        
        return $response;
    }
    
}
