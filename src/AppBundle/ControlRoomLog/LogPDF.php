<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

class LogPDF extends Controller
{
    /**
    * @Route("/logPDF/{sort}");
    * @Route("/logPDF/{sort}/");
    * @Route("/logPDF/{sort}/{filter}", name="PDF_sort_filter_log"); 
    * @Route("/logPDF/{sort}/{filter}/");
    * @Route("/logPDF/{sort}/{filter}/{filter_type}", name="PDF_sort_filter_type_log");
    * @Route("/logPDF/{sort}/{filter}/{filter_type}/");
    * @Route("/logPDF/", name="full_log_pdf");
    */
    
    public function logPDFAction($sort='DESC', $filter=null, $filter_type=null)
    {
        $sort_dir = $sort == 'ASC' ? 'ASC' : 'DESC';
        $em = $this->getDoctrine()->getManager();
        
        $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(
            array('event_active' => true));
        
        $em->flush();
        
        if ($event)
        {
            $eventId = $event->getId();
        } else {
            $eventId = 0;
        }
        
        $eventLatLong = explode(",",$event->getEventLatLong());
        $eventLat = $eventLatLong[0];
        $eventLong = $eventLatLong[1];
        $NEbound = $event->getNorthEastBounds();
        $SWbound = $event->getSouthWestBounds();
        $overlay = $event->getOverlayImageName();
 
        $logs = $em->getRepository('AppBundle\Entity\log_entries')->getLogEntries($eventId, $sort, $filter, $filter_type);
        
        $html = $this->renderView('logTable.html.twig', array('logs' => $logs));
        
        $dir = $this->get('kernel')->getRootDir();
        
        //$this->get('knp_snappy.pdf')->generate('http://www.google.com', '../media/PDFlogs/pdf_test.pdf');
        
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'logTable.html.twig',
                array(
                    'logs' => $logs,
                    'overlayFileName' =>$overlay,
                    'eventLat'=> $eventLat,
                    'eventLong'=> $eventLong,
                    'NEbound' => $NEbound,
                    'SWbound' => $SWbound
                )
            ),
            '../media/PDFlogs/pdf_test2.pdf'
        );
    }
}


