<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $entry = $em->getRepository('AppBundle\Entity\log_entries')->findOneBy(array('id' => $id));
        
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
        
        $dateDIR = date("Ymd-His");
        $filename = "Entry ".$entry->getId().".pdf";
        
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
            '../media/PDFlogs/'.$dateDIR.'/'.$filename
        );
        return $this->render('pdfEntry.html.twig', array('entry' => $entry, 'medical' => $medical, 'security' => $security, 'general' => $general, 'lost' => $lostProperty,));
    }
    
    /**
    * @Route("/PDFentry/active/", name="active_entries");
    * 
    */
    
    public function PDFActiveEntriesAction()
    {
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        
        $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(
            array('event_active' => true));
        
        $em->flush();
        
        //timestamp for directory
        $dateDIR = date("Ymd-His");
        
        //Setup array for the combined report
        $reports = array();
        
        //find all entries that are active
        $entries = $em->getRepository('AppBundle\Entity\log_entries')->findByEvent($event);
        foreach($entries as $entry)
        {
            $medical = $em->getRepository('AppBundle\Entity\medical_log')->findOneBy(array('log_entry_id' => $entry));
            if (!$medical){
                $medical = null;
            }

            $security = $em->getRepository('AppBundle\Entity\security_log')->findOneBy(array('log_entry_id' => $entry));
            if (!$security){
                $security = null;
            }

            $general = $em->getRepository('AppBundle\Entity\general_log')->findOneBy(array('log_entry_id' => $entry));
            if (!$general){
                $general = null;
            }

            $lostProperty = $em->getRepository('AppBundle\Entity\lost_property')->findOneBy(array('log_entry_id' => $entry));
            if (!$lostProperty){
                $lostProperty = null;
            }


            $filename = "Entry ".$entry->getId().".pdf";
            
            $reports[] = $this->renderView(
                    'pdfEntry.html.twig',
                    array(
                        'entry' => $entry,
                        'medical' => $medical,
                        'security' => $security,
                        'general' => $general,
                        'lost' => $lostProperty,
                    )
                );
            
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
                '../media/PDFlogs/'.$dateDIR.'/'.$filename
            );
        }
        //Generate full report
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $reports
            ,
            '../media/PDFlogs/'.$dateDIR.'/All Active.pdf'
        );
        return $this->render('pdfEntry.html.twig', array('entry' => $entry, 'medical' => $medical, 'security' => $security, 'general' => $general, 'lost' => $lostProperty,));
    }
}


