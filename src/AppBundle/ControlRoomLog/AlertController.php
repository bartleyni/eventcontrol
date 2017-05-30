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
        
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        
        $event = $usr->getSelectedEvent();
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
        
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        
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
    
    
    /**
     * @Route("/Alert/FCMTest/{key}/{id}", name="Alert_FCM_Test");
     * 
     */
    public function AlertFCMTestAction($key = null, $id = null)
    {
    
        #API access key from Google API's Console
            $registrationIds = $id;
        #prep the bundle
             $msg = array
                  (
                'body' 	=> '',
                'title'	=> '',
                'sound' => 'default'/*Default sound*/
                 );
                
            $fields = array
                    (
                        'to'		=> $registrationIds,
                        'notification'	=> $msg,
                        'data' => ["type" => 'UPS', "title" => 'Title', "msg" => 'Message',]
                  );

            $headers = array
                    (
                        'Authorization: key=' . $key,
                        'Content-Type: application/json'
                    );
        #Send Reponse To FireBase Server	
                $ch = curl_init();
                curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                curl_setopt( $ch,CURLOPT_POST, true );
                curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
                $result = curl_exec($ch );
                curl_close( $ch );
        #Echo Result Of FireBase Server
        #echo $result;
        
        $response = new Response($result,Response::HTTP_OK, array('content-type' => 'text/html'));
    
        return $response;
    }

    
}
