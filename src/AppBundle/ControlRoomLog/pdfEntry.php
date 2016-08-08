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
    * @Route("/PDFentry/log/{id}", name="PDF_entry");
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
    * @Route("/PDFentry/{eventId}", name="event_entries");
    * 
    */
    
    public function PDFEventEntriesAction($eventId=null)
    {   
        $em = $this->getDoctrine()->getManager();
        
        $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(
            array('id' => $eventId));
        $em->flush();
        
        if($event)
        {
            //timestamp for file
            $dateDIR = date("Ymd-His");
            //Event Directory
            $eventDIR = $event->getId().'-'.$event->getName();
            //Filename
            $ReportFilename = 'Full Report '.$dateDIR.'.pdf';

            //Setup array for the combined report
            $reports = array();
            
            //find all entries that are active
            $entries = $em->getRepository('AppBundle\Entity\log_entries')->findByEvent($event);
            $em->flush();
            
            if($entries)
            {
                foreach($entries as $entry)
                {
                    $medical = $em->getRepository('AppBundle\Entity\medical_log')->findOneBy(array('log_entry_id' => $entry));
                    if (!$medical){
                        $medical = null;
                    }
                    $em->flush();
                    
                    $security = $em->getRepository('AppBundle\Entity\security_log')->findOneBy(array('log_entry_id' => $entry));
                    if (!$security){
                        $security = null;
                    }
                    $em->flush();
                    
                    $general = $em->getRepository('AppBundle\Entity\general_log')->findOneBy(array('log_entry_id' => $entry));
                    if (!$general){
                        $general = null;
                    }
                    $em->flush();
                    
                    $lostProperty = $em->getRepository('AppBundle\Entity\lost_property')->findOneBy(array('log_entry_id' => $entry));
                    if (!$lostProperty){
                        $lostProperty = null;
                    }
                    $em->flush();
                    
                    $reports[] = $this->renderView(
                        'pdfEntry.html.twig',
                        array(
                            'entry' => $entry,
                            'medical' => $medical,
                            'security' => $security,
                            'general' => $general,
                            'lost' => $lostProperty,
                            //'event' => $event,
                        )
                    );
                }
                //Generate full report
                $this->get('knp_snappy.pdf')->generateFromHtml(
                    $reports,
                    '../media/PDFReports/'.$eventDIR.'/'.$ReportFilename
                );
                
                //log file in event system
                $em->flush();
                $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(array('id' => $eventId));
                $event->setEventReportFilename($ReportFilename);
                $event->setEventReportRunDate(new \DateTime());
                $em->persist($event);
                $em->flush();
            }
            return $this->render('pdfEntry.html.twig', array('entry' => $entry, 'medical' => $medical, 'security' => $security, 'general' => $general, 'lost' => $lostProperty, 'event' => $event,));
        }
        //return $this->redirectToRoute('event_list');
    }
}


