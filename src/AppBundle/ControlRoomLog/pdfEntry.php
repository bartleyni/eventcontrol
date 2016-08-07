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
            $medical = null;
        }
        
        $security = $em->getRepository('AppBundle\Entity\security_log')->findOneBy(array('log_entry_id' => $id));
        if (!$security){
            $security = null;
        }
        
        $general = $em->getRepository('AppBundle\Entity\general_log')->findOneBy(array('log_entry_id' => $id));
        if (!$general){
            $general = null;
        }
        
        $lostProperty = $em->getRepository('AppBundle\Entity\lost_property')->findOneBy(array('log_entry_id' => $id));
        if (!$lostProperty){
                $lostProperty = null;
        }
        
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'pdfEntry.html.twig',
                array(
                    'entry' => $entry,
                    'medical' => $medical,
                    'security' => $security,
                    'general' => $general,
                    'lost' => $lostProperty,
                )
            ),
            '../media/PDFlogs/pdf_test3.pdf'
        );
        
        
        return $this->render('pdfEntry.html.twig', array('entry' => $entry, 'medical' => $medical, 'security' => $security, 'general' => $general, 'lost' => $lostProperty,));
    }
}


