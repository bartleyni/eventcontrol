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

class pdfEntry extends Controller
{
    /**
    * @Route("/PDFentry/{id}", name="PDF_entry");
    * 
    */
    
    public function PDFentryAction($id=null)
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
        $medicalTab = null;
        $securityTab = null;
        $generalTab = null;
        $lostTab = null;
        $medicalClosed = null;
        $securityClosed = null;
        $generalClosed = null;
        $lostClosed = null;
        
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
        
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'pdfEntry.html.twig',
                array(
                    'medicalTab' => $medicalTab,
                    'securityTab' => $securityTab,
                    'generalTab' => $generalTab,
                    'lostTab' => $lostTab,
                    'medicalClosed' => $medicalClosed,
                    'securityClosed' => $securityClosed,
                    'lostClosed' => $lostClosed,
                    'generalClosed' => $generalClosed,
                    'log_entry' => $form->createView(),
                    'general_entry' => $generalForm->createView(),
                    'medical_entry' => $medicalForm->createView(),
                    'security_entry' => $securityForm->createView(),
                    'lost_entry' => $lostPropertyForm->createView(),
                )
            ),
            '../media/PDFlogs/pdf_test3.pdf'
        );
        
        
        return $this->render('pdfEntry.html.twig', array('medicalTab' => $medicalTab, 'securityTab' => $securityTab, 'generalTab' => $generalTab, 'lostTab' => $lostTab, 'medicalClosed' => $medicalClosed, 'securityClosed' => $securityClosed, 'lostClosed' => $lostClosed, 'generalClosed' => $generalClosed, 'log_entry' => $form->createView(),'general_entry' => $generalForm->createView(),'medical_entry' => $medicalForm->createView(),'security_entry' => $securityForm->createView(),'lost_entry' => $lostPropertyForm->createView(),));
    }
}


