<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Form\Type\LogType;
use AppBundle\Form\Type\MedicalType;
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
                
        $new_general = new general_log();        
        $new_general->setLogEntryId($entry);
        $new_lost_property = new lost_property();
        $new_lost_property->setLogEntryId($entry);

        $new_security = new security_log();
        $new_security->setLogEntryId($entry);
        /*$generalForm = $this->createForm(new GeneralType(), $new__general, array(
            'method' => 'POST',
        ));
        $lostPropertyForm = $this->createForm(new LostPropertyType(), $new_lost_property, array(
            'method' => 'POST',
        ));*/
        $medicalForm = $this->createForm(new MedicalType(), $medical, array(
            'method' => 'POST',
        ));
        /*$securityForm = $this->createForm(new SecurityType(), $new_security, array(
            'method' => 'POST',
        ));*/
        
        if ($request->getMethod() == 'POST') {
            
            
            if($request->request->has('log_entry')){
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $em->persist($entry);
                    $em->flush();
                    return $this->redirect($this->generateUrl('edit_entry', array('id' => $entry->getId())));
                }
            }
            
            if($request->request->has('medical_entry')){
                $medicalForm->handleRequest($request);
                if ($medicalForm->isValid()) {
                    $em->persist($medical);
                    $em->flush();
                    return $this->redirect($this->generateUrl('edit_entry', array('id' => $entry->getId())));
                }
            }
        }
        else
        {
            //return $this->redirect('../log/');
        }
        return $this->render('editForm.html.twig', array('log_entry' => $form->createView(),'medical_entry' => $medicalForm->createView(),));
    }
}


