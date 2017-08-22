<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use RMS\PushNotificationsBundle\Message\AndroidMessage;

use AppBundle\Form\Type\LogType;
use AppBundle\Form\Type\MedicalType;
use AppBundle\Form\Type\GeneralType;
use AppBundle\Form\Type\SecurityType;
use AppBundle\Form\Type\LostPropertyType;
use AppBundle\Entity\log_entries;
use AppBundle\Entity\general_log;
use AppBundle\Entity\medical_log;
use AppBundle\Entity\security_log;
use AppBundle\Entity\lost_property;

class edit_entry_form extends Controller
{
    /**
    * @Route("/entry/{id}", name="edit_entry");
    * 
    */
    
    public function entryAction($id=null, Request $request)
    {

        if (is_null($id)) {
            $postData = $request->get('entry');
            $id = $postData['id'];
        }
        
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                throw $this->createAccessDeniedException();
            }
        
        $editable = false;
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $editable = true;
        }
        
        //find the entry
        $em = $this->getDoctrine()->getManager();
        $entry = $em->getRepository('AppBundle\Entity\log_entries')->find($id);
        if ($editable){
            $form = $this->createForm(LogType::class, $entry);
        } else {
            $form = $this->createForm(LogType::class, $entry, array(
                'disabled' => true,
            ));
        }
        $medicalTab = null;
        $securityTab = null;
        $generalTab = null;
        $lostTab = null;
        $medicalClosed = null;
        $securityClosed = null;
        $generalClosed = null;
        $lostClosed = null;
        
        $em->flush();
        
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        $operatorId = $usr->getId();
        
        $activeEvent = $usr->getSelectedEvent();
        
        $logFiles = $em->getRepository('AppBundle\Entity\logFile')->findBy(array('log_entry' => $id));
        
        if($activeEvent != $entry->getEvent())
        {
            throw $this->createAccessDeniedException();
        }
        
        //Get the Lat and Long from the event
        
        $entryEvent = $entry->getEvent();
        $eventLatLong = explode(",",$entryEvent->getEventLatLong());
        $eventLat = $eventLatLong[0];
        $eventLong = $eventLatLong[1];
        $NEbound = $entryEvent->getNorthEastBounds();
        $SWbound = $entryEvent->getSouthWestBounds();
        $overlay = $entryEvent->getOverlayImageName();
        
        //get original entry Longitude for test on submit
        $origLongitude = $entry->getLongitude();
        
        $medical = $em->getRepository('AppBundle\Entity\medical_log')->findOneBy(array('log_entry_id' => $id));
        if (!$medical){
                $medical = new medical_log();
                $medical->setLogEntryId($entry);
                $medicalTab = null;
            } else {
                $medicalTab = $medical;
                $medicalClosed = $medical->getMedicalEntryClosedTime();
            }
        $security = $em->getRepository('AppBundle\Entity\security_log')->findOneBy(array('log_entry_id' => $id));
        if (!$security){
                $security = new security_log();
                $security->setLogEntryId($entry);
                $securityTab = null;
            } else {
                $securityTab = $security;
                $securityClosed = $security->getSecurityEntryClosedTime();
            }
        $general = $em->getRepository('AppBundle\Entity\general_log')->findOneBy(array('log_entry_id' => $id));
        if (!$general){
                $general = new general_log();
                $general->setLogEntryId($entry);
                $general->setGeneralOpen(true);
                $generalTab = null;
            } else {
                $generalTab = $general;
                $generalClosed = $general->getGeneralEntryClosedTime();
            }
        $lostProperty = $em->getRepository('AppBundle\Entity\lost_property')->findOneBy(array('log_entry_id' => $id));
        if (!$lostProperty){
                $lostProperty = new lost_property();
                $lostProperty->setLogEntryId($entry);
                $lostTab = null;
            } else {
                $lostTab = $lostProperty;
                $lostClosed = $lostProperty->getLostPropertyEntryClosedTime();
            }
            
        //Check to see if use can edit this entry!
        if($editable){
            $generalForm = $this->createForm(GeneralType::class, $general, array(
                'method' => 'POST',
            ));
            $lostPropertyForm = $this->createForm(LostPropertyType::class, $lostProperty, array(
                'method' => 'POST',
            ));
            $medicalForm = $this->createForm(MedicalType::class, $medical, array(
                'method' => 'POST',
            ));
            $securityForm = $this->createForm(SecurityType::class, $security, array(
                'method' => 'POST',
            ));
        } else {
            $generalForm = $this->createForm(GeneralType::class, $general, array(
                'method' => 'POST',
                'disabled' => true,
            ));
            $lostPropertyForm = $this->createForm(LostPropertyType::class, $lostProperty, array(
                'method' => 'POST',
                'disabled' => true,
            ));
            $medicalForm = $this->createForm(MedicalType::class, $medical, array(
                'method' => 'POST',
                'disabled' => true,
            ));
            $securityForm = $this->createForm(SecurityType::class, $security, array(
                'method' => 'POST',
                'disabled' => true,
            ));
        }
        
        if ($request->getMethod() == 'POST') {
            
            $form->handleRequest($request);
            $medicalForm->handleRequest($request);
            $generalForm->handleRequest($request);
            $securityForm->handleRequest($request);
            $lostPropertyForm->handleRequest($request);
            
            if ($form->isValid()) {
                if ($form->get('submit')->isClicked()){
                    $entry->setLogEntryOpenTime(new \DateTime($form->get('log_entry_open_time')));
                    $entry->setLogUpdateTimestamp(new \DateTime());
                    //Lookup log location in event location entity and if latlong is blank use data
                    if($entry->getGeolocated() == false){
                        $log_location = $form['location']->getData();
                        $location = $em->getRepository('AppBundle\Entity\Locations')->findOneBy(array('event' => $entryEvent, 'locationText' => $log_location));
                        if($location){
                            $latLong = explode(", ", $location->getLocationLatLong());
                            $entry->setLatitude($latLong[0]);
                            $entry->setLongitude($latLong[1]);
                        }
                    }
                    
                    $em->persist($entry);
                    $em->flush();
                }
            }
            if ($medicalForm->isValid()) {    
                if ($medicalForm->get('submit_medical')->isClicked()){
                    if ($medical->getMedicalResolution()){
                        $medical->setMedicalEntryClosedTime(new \DateTime());
                    }
                    $em->persist($medical);
                    $em->flush();
                    $entry->setLogUpdateTimestamp(new \DateTime());
                    $em->persist($entry);
                    $em->flush();
                }
                    return $this->redirect($this->generateUrl('edit_entry', array('id' => $entry->getId())));
            }
            if ($generalForm->isValid()) {    
                if ($generalForm->get('submit_general')->isClicked()){
                    $generalOpen = $general->getGeneralOpen();
                    if ($generalOpen == false){
                        $general->setGeneralEntryClosedTime(new \DateTime());
                    }
                    $em->persist($general);
                    $em->flush();
                    $entry->setLogUpdateTimestamp(new \DateTime());
                    $em->persist($entry);
                    $em->flush();
                }
                    return $this->redirect($this->generateUrl('edit_entry', array('id' => $entry->getId())));
            }
            if ($securityForm->isValid()) {    
                if ($securityForm->get('submit_security')->isClicked()){
                    if ($security->getSecurityResolution()){
                        $security->setSecurityEntryClosedTime(new \DateTime());
                    }
                    $em->persist($security);
                    $em->flush();
                    $entry->setLogUpdateTimestamp(new \DateTime());
                    $em->persist($entry);
                    $em->flush();
                }
                    return $this->redirect($this->generateUrl('edit_entry', array('id' => $entry->getId())));
            }
            if ($lostPropertyForm->isValid()) {    
                if ($lostPropertyForm->get('submit_lost_property')->isClicked()){
                    if ($lostProperty->getLostPropertyResolution()){
                        $lostProperty->setLostPropertyEntryClosedTime(new \DateTime());
                    }    
                    $em->persist($lostProperty);
                    $em->flush();
                    $entry->setLogUpdateTimestamp(new \DateTime());
                    $em->persist($entry);
                    $em->flush();
                }
                    return $this->redirect($this->generateUrl('edit_entry', array('id' => $entry->getId())));
            }
        }
        else
        {
            //return $this->redirect('../log/');
        }
        return $this->render('editForm.html.twig', array('entry'=> $entry, 'logFiles' =>$logFiles, 'overlayFileName' =>$overlay, 'eventLat'=> $eventLat, 'eventLong'=> $eventLong, 'NEbound' => $NEbound,'SWbound' => $SWbound, 'medicalTab' => $medicalTab, 'securityTab' => $securityTab, 'generalTab' => $generalTab, 'lostTab' => $lostTab, 'medicalClosed' => $medicalClosed, 'securityClosed' => $securityClosed, 'lostClosed' => $lostClosed, 'generalClosed' => $generalClosed, 'log_entry' => $form->createView(),'general_entry' => $generalForm->createView(),'medical_entry' => $medicalForm->createView(),'security_entry' => $securityForm->createView(),'lost_entry' => $lostPropertyForm->createView(),));
    }
    
    /**
     * @Route("/entry/lookup/location/{location}", name="location_lookup");
     * @Route("/entry/lookup/location/");
     * 
     */
    public function LocationLookupAction($location=null)
    {
        $em = $this->getDoctrine()->getManager();
        
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        $operatorId = $usr->getId();
        //$activeEvent = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        $activeEvent = $usr->getSelectedEvent();
        $em->flush();
        
        $lookup1 = $em->getRepository('AppBundle\Entity\log_entries')->getLocationLookup($activeEvent, $location);
        $lookup2 = $em->getRepository('AppBundle\Entity\Locations')->getEventLocationLookup($activeEvent, $location);
        
        $lookup3 = array_unique(array_merge_recursive($lookup1,$lookup2),SORT_REGULAR);
        
        $lookup = array();
        
        foreach($lookup3 as $val)
        {
            array_push($lookup, $val);
        }
        
        
        $response = new JsonResponse();
        $response->setData($lookup);
        
        return $response;
    }
    
    /**
     * @Route("/entry/lookup/reportedBy/{reported}", name="reported_by_lookup");
     * 
     */
    public function ReportedByLookupAction($reported=null)
    {
        $em = $this->getDoctrine()->getManager();
        
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        $operatorId = $usr->getId();
        //$activeEvent = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        $activeEvent = $usr->getSelectedEvent();
        $em->flush();
        
        $lookup = $em->getRepository('AppBundle\Entity\log_entries')->getReportedByLookup($activeEvent, $reported);
        
        $response = new JsonResponse();
        $response->setData($lookup);
        
        return $response;
    }
}
