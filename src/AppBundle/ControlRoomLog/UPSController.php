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
use AppBundle\Form\Type\UPSType;
use RedjanYm\FCMBundle;

/**
 * Description of UPSController
 *
 * @author Nick
 */
class UPSController extends Controller
{
    
    /**
    * @Route("/UPS/new/", name="new_ups");
    */    
    
    public function newUPSAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $ups = new UPS();
        $form = $this->createForm(UPSType::class, $ups);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            
            foreach ($form['Events']->getData()->getValues() as $v) {
                $event = $em->getRepository('AppBundle:event')->find($v->getId());
                if ($event) {
                    $event->addUPSs($$ups);
                    $em->persist($event);
                }
            }
            $em->persist($ups);
            $upsId = $ups->getId();
            $em->flush();

            return $this->redirectToRoute('ups_list');
        }
        
        return $this->render(
            'upsForm.html.twig',
            array('form' => $form->createView())
        );
    }
    
    /**
    * @Route("/UPS/edit/{editId}", name="edit_ups");
    */
    
    public function editUPSAction(Request $request, $editId=null)
    {
        $em = $this->getDoctrine()->getManager();
        
        if ($editId){
            $ups = $em->getRepository('AppBundle\Entity\UPS')->findOneBy(array('id' => $editId));
            $em->flush();
            
            $form = $this->createForm(UPSType::class, $ups, array('ups_id' => $editId, 'em' => $em));
            $form->handleRequest($request);
        }

        if ($form->isSubmitted() && $form->isValid()) {

//            foreach ($form['Events']->getData()->getValues() as $v) {
//                $event = $em->getRepository('AppBundle:event')->find($v->getId());
//                if ($event) {
//                    $event->addUPSs($ups);
//                    $em->persist($event);
//                }
//            }
            
            $em->persist($ups);
            $em->flush();

            return $this->redirectToRoute('ups_list');
        }
        
        return $this->render(
            'upsForm.html.twig',
            array('form' => $form->createView(), 'ups' => $ups)
        );
    }
    
    /**
    * @Route("/UPS/delete/{deleteId}", name="delete_ups");
    */
    
    public function deletUPSAction(Request $request, $deleteId=null)
    {
        $em = $this->getDoctrine()->getManager();
                
        if ($deleteId){
            $event = $em->getRepository('AppBundle\Entity\UPS')->findOneBy(array('id' => $deleteId));
            if ($ups) {
                $em->remove($ups);
                $em->flush();
            }
        }
        
        return $this->redirectToRoute('ups_list');
    }
    
    /**
    * @Route("/UPS/", name="ups_list");
    */
    
    public function listUPSAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $qb = $em->createQueryBuilder(); 
        
        $qb
            ->select('UPS.id, UPS.location, UPS.name, UPS.power')
            ->from('AppBundle\Entity\UPS', 'UPS')
            ->orderBy('UPS.id', 'ASC')
            ;
        
        $query = $qb->getQuery();
        $UPSs = $query->getResult();
        
        if($UPSs)
        {
            foreach ($UPSs as $key => $UPS)
            {
                $UPSs[$key]["Last"] = $em->getRepository('AppBundle\Entity\UPS_Status')->getLatestSpecificUPS($UPS["id"]);
            }
        }
                 
        //$last_status = $em->getRepository('AppBundle\Entity\UPS_Status')->getLatestSpecificUPS($ups_id);
        
        return $this->render('upsList.html.twig', array('ListUPSs' => $UPSs));
    }
    
    /**
     * @Route("/UPS/status/{event_id}", name="UPS_status");
     * 
     */
    public function UPSAction(Request $request, $event_id = null)
    {
        $em = $this->getDoctrine()->getManager();
        
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
     * @Route("/UPS/json/{key}/{event_id}", name="UPS_json_status");
     * 
     */
    public function UPSjsonAction($key, $event_id = null)
    {
            $lookup_key = $this->getParameter('ups_key');
            if ($lookup_key == $key){
                $em = $this->getDoctrine()->getManager();

                $ups_statuses = $em->getRepository('AppBundle\Entity\UPS_Status')->getLatestUPS($event_id);

                if ($ups_statuses)
                {
                        $response = new JsonResponse();
                        $response->setData($ups_statuses);

                } else {
                    $response->setContent('No Status');
                    $response->headers->set('Content-Type', 'text/plain');
                    $response->setStatusCode(Response::HTTP_NOT_FOUND);
                }
            } else {
                $response = new Response();
                $response->setContent('Unauthorised Access');
                $response->headers->set('Content-Type', 'text/plain');
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
                $response->send();
            }
        
        return $response;
    }
    
    /**
     * @Route("/UPS/test/{ups_id}", name="UPS_test");
     * 
     */
    public function UPSTestAction(Request $request, $ups_id = null)
    {
        $em = $this->getDoctrine()->getManager();
        
        $last_status = $em->getRepository('AppBundle\Entity\UPS_Status')->getLatestSpecificUPS($ups_id);
        
        if ($last_status)
        {
                $response = new JsonResponse();
                $response->setData($last_status);

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
        
        $last_status = $em->getRepository('AppBundle\Entity\UPS_Status')->getLatestSpecificUPS($id);
        
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
    
        $fcmClient = $this->get('redjan_ym_fcm.client');
        $notification = $fcmClient-->createDeviceNotification(
            $ups->getName(),
            $UPSstatus->getStatus(),
            'eKirY29t09I:APA91bGDUn-rq0Iai6NEmC7Pmi1sE_cvdglGU1aPW4NxqRRZ8U-F_rP4ZAN_vkc-tctRpzPjgy8UqUKrDPiPX6x2p7YoFz4NgO3QsukOEvWjJDcyx6bS43RUq1i986N6rtD-2tlt7fD6'
        );
        $notification->setData(["type" => "",]);
        $fcmClient->sendNotification($notification);
        
        if($status!=$last_status['status']){
            $alert = new Alert();
            $alert->setTitle($ups.' '.$status);
            $alert->setMessage('UPS: '.$ups.'<br>Status: '.$status.'<br>Location: '.$ups->getLocation());
            $alert->setURL(null);
            if($status=="Fault"){
                $alert->setType("danger");
            }
            else if($status=="Mains") {
                $alert->setType("info");
            }
            else {
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
/*        
        $client   = $this->get('dz.slack.client');
        $slackrResponse = $client->send(
            \DZunke\SlackBundle\Slack\Client\Actions::ACTION_POST_MESSAGE,
            [
                'identity' => $this->get('dz.slack.identity_bag')->get('echo_charlie'),
                'channel'  => '#alerts',
                'text'     => 'The '.$ups.' UPS which is located at '.$ups->getLocation().', has updated with the following status: '.$status
            ]
        ); */
        
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
        
        $last_status = $em->getRepository('AppBundle\Entity\UPS_Status')->getLatestSpecificUPS($id);
        
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
        
        if($status!=$last_status['status']){
            $alert = new Alert();
            $alert->setTitle($ups.' '.$status);
            $alert->setMessage('UPS: '.$ups.'<br>Status: '.$status.'<br>Location: '.$ups->getLocation().'<br>Line Voltage: '.$line.' Volts AC<br>Load: '.$load.'%<br>Battery Voltage: '.$battery.' Volts DC<br>Time Remaining: '.$time.'minutes');
            $alert->setURL(null);
            if($status=="Fault"){
                $alert->setType("danger");
            }
            else if($status=="Mains") {
                $alert->setType("info");
            }
            else {
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
 /*         
        $client   = $this->get('dz.slack.client');
        $slackrResponse = $client->send(
            \DZunke\SlackBundle\Slack\Client\Actions::ACTION_POST_MESSAGE,
            [
                'identity' => $this->get('dz.slack.identity_bag')->get('echo_charlie'),
                'channel'  => '#alerts',
                'text'     => "The ".$ups." UPS which is located at ".$ups->getLocation().", has updated with the following status: ".$status." \n Line Voltage: ".$line." Volts AC \n Load: ".$load."% \n Battery Voltage: ".$battery." Volts DC \n Time Remaining: ".$time." minutes"
            ]
        ); */
        
        $response = new Response('UPS updated',Response::HTTP_OK, array('content-type' => 'text/html'));

        return $response;
    }
    
}
