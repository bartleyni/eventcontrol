<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

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
    
    public function entryAction($id=null)
    {
        $request = $this->getRequest();

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
            $form = $this->createForm(new LogType(), $entry);
        } else {
            $form = $this->createForm(new LogType(), $entry, array(
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
        
        $usr = $this->get('security.context')->getToken()->getUser();
        $operatorId = $usr->getId();
        
        $activeEvent = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        
        if($activeEvent != $entry->getEvent())
        {
            throw $this->createAccessDeniedException();
        }
        
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
            $generalForm = $this->createForm(new GeneralType(), $general, array(
                'method' => 'POST',
            ));
            $lostPropertyForm = $this->createForm(new LostPropertyType(), $lostProperty, array(
                'method' => 'POST',
            ));
            $medicalForm = $this->createForm(new MedicalType(), $medical, array(
                'method' => 'POST',
            ));
            $securityForm = $this->createForm(new SecurityType(), $security, array(
                'method' => 'POST',
            ));
        } else {
            $generalForm = $this->createForm(new GeneralType(), $general, array(
                'method' => 'POST',
                'disabled' => true,
            ));
            $lostPropertyForm = $this->createForm(new LostPropertyType(), $lostProperty, array(
                'method' => 'POST',
                'disabled' => true,
            ));
            $medicalForm = $this->createForm(new MedicalType(), $medical, array(
                'method' => 'POST',
                'disabled' => true,
            ));
            $securityForm = $this->createForm(new SecurityType(), $security, array(
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
                    $entry->setLogUpdateTimestamp(new \DateTime());
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
        return $this->render('editForm.html.twig', array('medicalTab' => $medicalTab, 'securityTab' => $securityTab, 'generalTab' => $generalTab, 'lostTab' => $lostTab, 'medicalClosed' => $medicalClosed, 'securityClosed' => $securityClosed, 'lostClosed' => $lostClosed, 'generalClosed' => $generalClosed, 'log_entry' => $form->createView(),'general_entry' => $generalForm->createView(),'medical_entry' => $medicalForm->createView(),'security_entry' => $securityForm->createView(),'lost_entry' => $lostPropertyForm->createView(),));
    }
    
    /**
     * @Route("/entry/lookup/location/{location}", name="location_lookup");
     * 
     */
    public function LocationLookupAction($location=null)
    {
        $em = $this->getDoctrine()->getManager();
        
        $usr = $this->get('security.context')->getToken()->getUser();
        $operatorId = $usr->getId();
        $activeEvent = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        $em->flush();
        
        $lookup = $em->getRepository('AppBundle\Entity\log_entries')->getLocationLookup($activeEvent, $location);
        
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
        
        $usr = $this->get('security.context')->getToken()->getUser();
        $operatorId = $usr->getId();
        $activeEvent = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        $em->flush();
        
        $lookup = $em->getRepository('AppBundle\Entity\log_entries')->getReportedByLookup($activeEvent, $reported);
        
        $response = new JsonResponse();
        $response->setData($lookup);
        
        return $response;
    }
}
