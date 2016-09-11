<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

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
        
        $oldFilename = $event->getEventReportFilename();
        
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
            //$em->flush();
            
            $reports[] = $this->renderView(
                        'pdfSummary.html.twig',
                        array(
                            'event' => $event,
                        )
                    );
            
            if($entries)
            {
                foreach($entries as $entry)
                {
                    $medical = $em->getRepository('AppBundle\Entity\medical_log')->findOneBy(array('log_entry_id' => $entry));
                    if (!$medical){
                        $medical = null;
                    }
                    //$em->flush();
                    
                    $security = $em->getRepository('AppBundle\Entity\security_log')->findOneBy(array('log_entry_id' => $entry));
                    if (!$security){
                        $security = null;
                    }
                    //$em->flush();
                    
                    $general = $em->getRepository('AppBundle\Entity\general_log')->findOneBy(array('log_entry_id' => $entry));
                    if (!$general){
                        $general = null;
                    }
                    //$em->flush();
                    
                    $lostProperty = $em->getRepository('AppBundle\Entity\lost_property')->findOneBy(array('log_entry_id' => $entry));
                    if (!$lostProperty){
                        $lostProperty = null;
                    }
                    //$em->flush();
                    
                    $reports[] = $this->renderView(
                        'pdfEntry.html.twig',
                        array(
                            'entry' => $entry,
                            'medical' => $medical,
                            'security' => $security,
                            'general' => $general,
                            'lost' => $lostProperty,
                            'event' => $event,
                            
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
                
                $fs = new Filesystem();
                
                try{
                    $OldFileExists = $fs->exists('../media/PDFReports/'.$eventDIR.'/'.$oldFilename);
                    if($OldFileExists){
                        $fs->remove('../media/PDFReports/'.$eventDIR.'/'.$oldFilename);
                    }
                } catch (IOExceptionInterface $e) {
                    
                }
            }
            
            //return $this->render('pdfEntry.html.twig', array('entry' => $entry, 'medical' => $medical, 'security' => $security, 'general' => $general, 'lost' => $lostProperty, 'event' => $event,));
        }
        return $this->redirectToRoute('event_list');
    }
    
        
    /**
    * @Route("/PDFview/{eventId}", name="event_report_view");
    * 
    */
    
    public function PDFEventViewAction($eventId=null)
    {  
        $em = $this->getDoctrine()->getManager();
        
        $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(
            array('id' => $eventId));
        $em->flush();
        
        if($event)
        {
            $eventDIR = $event->getId().'-'.$event->getName();
            $eventFile = $event->getEventReportFilename();
            
            //return new BinaryFileResponse('../media/PDFReports/'.$eventDIR.'/'.$eventFile);
            
            return $this->render('iframe.html.twig', array('pdfFile' => $eventFile, 'pdfPath' => '../media/PDFReports/'.$eventDIR));
        }
    
    }
    
}


