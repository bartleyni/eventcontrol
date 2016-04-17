<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        
        //find the entry
        $em = $this->getDoctrine()->getManager();
        $entry = $em->getRepository('AppBundle\Entity\log_entries')->find($id);
        $form = $this->createForm(new LogType(), $entry);
        $medical = $em->getRepository('AppBundle\Entity\medical_log')->findOneBy(array('log_entry_id' => $id));
        if (!$medical){
                $medical = new medical_log();
                $medical->setLogEntryId($entry);
            }
        $security = $em->getRepository('AppBundle\Entity\security_log')->findOneBy(array('log_entry_id' => $id));
        if (!$security){
                $security = new security_log();
                $security->setLogEntryId($entry);
            }
        $general = $em->getRepository('AppBundle\Entity\general_log')->findOneBy(array('log_entry_id' => $id));
        if (!$general){
                $general = new general_log();
                $general->setLogEntryId($entry);
            }
        $lostProperty = $em->getRepository('AppBundle\Entity\lost_property')->findOneBy(array('log_entry_id' => $id));
        if (!$lostProperty){
                $lostProperty = new lost_property();
                $lostProperty->setLogEntryId($entry);
            }
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
        
        if ($request->getMethod() == 'POST') {
            
            $form->handleRequest($request);
            $medicalForm->handleRequest($request);
            $generalForm->handleRequest($request);
            $securityForm->handleRequest($request);
            $lostPropertyForm->handleRequest($request);
            
            if ($form->isValid()) {
                if ($form->get('submit')->isClicked()){
                    $em->persist($entry);
                    $em->flush();
                }
            }
            if ($medicalForm->isValid()) {    
                if ($medicalForm->get('submit_medical')->isClicked()){
                    $em->persist($medical);
                    $em->flush();
                }
                    return $this->redirect($this->generateUrl('edit_entry', array('id' => $entry->getId())));
            }
            if ($generalForm->isValid()) {    
                if ($generalForm->get('submit_general')->isClicked()){
                    $em->persist($general);
                    $em->flush();
                }
                    return $this->redirect($this->generateUrl('edit_entry', array('id' => $entry->getId())));
            }
            if ($securityForm->isValid()) {    
                if ($securityForm->get('submit_security')->isClicked()){
                    $em->persist($security);
                    $em->flush();
                }
                    return $this->redirect($this->generateUrl('edit_entry', array('id' => $entry->getId())));
            }
            if ($lostPropertyForm->isValid()) {    
                if ($lostPropertyForm->get('submit_lostProperty')->isClicked()){
                    $em->persist($lostProperty);
                    $em->flush();
                }
                    return $this->redirect($this->generateUrl('edit_entry', array('id' => $entry->getId())));
            }
        }
        else
        {
            //return $this->redirect('../log/');
        }
        return $this->render('editForm.html.twig', array('log_entry' => $form->createView(),'general_entry' => $generalForm->createView(),'medical_entry' => $medicalForm->createView(),'security_entry' => $securityForm->createView(),'lost_entry' => $lostPropertyForm->createView(),));
    }
}


