<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\log_entriesRepository;

class Log extends Controller
{
    /**
    * @Route("/log.{_format}", name="log_pdf");
    * @Route("/log/{sort}", name="sort_log");
    * @Route("/log/{sort}/");
    * @Route("/log/{sort}/{filter}", name="sort_filter_log"); 
    * @Route("/log/{sort}/{filter}/");
    * @Route("/log/{sort}/{filter}/{filter_type}", name="sort_filter_type_log");
    * @Route("/log/{sort}/{filter}/{filter_type}/");
    * @Route("/log/", name="full_log");
    * @Route("/"); 
    */
    
    public function logAction($sort='DESC', $filter=null, $filter_type=null, $_format="html")
    {
        $sort_dir = $sort == 'ASC' ? 'ASC' : 'DESC';
        $em = $this->getDoctrine()->getManager();
        
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        $event = $usr->getSelectedEvent();
        
        if ($event)
        {
            $eventId = $event->getId();
        } else {
            $eventId = 0;
        }
        
        $em->flush();

        $logs = $em->getRepository('AppBundle\Entity\log_entries')->getLogEntries($eventId, $sort, $filter, $filter_type);
        print_r($_format);
       if ($_format=="pdf") {
            $pageUrl = $this->generateUrl('full_log', array(), true); // use absolute path!

            return new Response(
                $this->get('knp_snappy.pdf')->getOutput($pageUrl),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="log.pdf"'
                )
            );
        }else {
            return $this->render('log2.html.twig', array('logs' => $logs));
        }
    }
    
    /**
    * @Route("/table/{sort}", name="sort_log_table");
    * @Route("/table/{sort}/");
    * @Route("/table/{sort}/{filter}", name="sort_filter_log_table"); 
    * @Route("/table/{sort}/{filter}/");
    * @Route("/table/{sort}/{filter}/{filter_type}", name="sort_filter_type_log_table");
    * @Route("/table/{sort}/{filter}/{filter_type}/");
    * @Route("/table/", name="full_log_table");
    */
    
    public function tableAction($sort='DESC', $filter=null, $filter_type=null)
    {
        $em = $this->getDoctrine()->getManager();
        
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        $operatorId = $usr->getId();

        //$event = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        $event = $usr->getSelectedEvent();
        $em->flush();
        
        if ($event)
        {
            $eventId = $event->getId();
        } else {
            $eventId = 0;
        }
        
        $logs = $em->getRepository('AppBundle\Entity\log_entries')->getLogEntries($eventId, $sort, $filter, $filter_type);
        return $this->render('logTable2.html.twig', array('logs' => $logs));
    }
    
    /**
     * @Route("/logjsondata/", name="log_json_data");
     *
     */
    public function log_json_data(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        $operatorId = $usr->getId();
        //$event = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        $event = $usr->getSelectedEvent();
        //$user_event = $em->getRepository('AppBundle\Entity\user_events')->findOneBy(array('User_id' => $operatorId, 'active' => true));
        $em->flush();
        
        if ($event)
        {
            $eventId = $event->getId();
        } else {
            $eventId = 0;
        }
        
        $em->flush();
        
        $qb1 = $em->createQueryBuilder(); 
        
        $qb1
            ->select('count(entry.id)')
            ->from('AppBundle\Entity\log_entries', 'entry')
            ->leftJoin('AppBundle\Entity\event', 'event', 'WITH', 'event.id = entry.event')
            ->where('event.id = :eventId')
            ->setParameter('eventId', $eventId)
            ;

        $logs['Total'] = $qb1->getQuery()->getSingleScalarResult();
        
        $qb2 = $em->createQueryBuilder(); 
        
        $qb2
            ->select('count(entry.id)')
            ->from('AppBundle\Entity\log_entries', 'entry')
            ->leftJoin('AppBundle\Entity\medical_log', 'med', 'WITH', 'med.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\event', 'event', 'WITH', 'event.id = entry.event')
            ->where($qb2->expr()->isNotNull('med.medical_description'))
            ->andWhere('event.id = :eventId')
            ->setParameter('eventId', $eventId)
            ;

        $logs['Medical'] = $qb2->getQuery()->getSingleScalarResult();
        
        $qb3 = $em->createQueryBuilder(); 
        
        $qb3
            ->select('count(entry.id)')
            ->from('AppBundle\Entity\log_entries', 'entry')
            ->leftJoin('AppBundle\Entity\security_log', 'sec', 'WITH', 'sec.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\event', 'event', 'WITH', 'event.id = entry.event')
            ->where($qb3->expr()->isNotNull('sec.security_description'))
            ->andWhere('event.id = :eventId')
            ->setParameter('eventId', $eventId)
            ;

        $logs['Security'] = $qb3->getQuery()->getSingleScalarResult();
        
        $qb4 = $em->createQueryBuilder(); 
        
        $qb4
            ->select('count(entry.id)')
            ->from('AppBundle\Entity\log_entries', 'entry')
            ->leftJoin('AppBundle\Entity\lost_property', 'lost', 'WITH', 'lost.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\event', 'event', 'WITH', 'event.id = entry.event')
            ->where($qb4->expr()->isNotNull('lost.lost_property_description'))
            ->andWhere('event.id = :eventId')
            ->setParameter('eventId', $eventId)
            ;

        $logs['Lost'] = $qb4->getQuery()->getSingleScalarResult();
        
        $qb5 = $em->createQueryBuilder(); 
        
        $qb5
            ->select('count(entry.id)')
            ->from('AppBundle\Entity\log_entries', 'entry')
            ->leftJoin('AppBundle\Entity\general_log', 'gen', 'WITH', 'gen.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\security_log', 'sec', 'WITH', 'sec.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\medical_log', 'med', 'WITH', 'med.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\lost_property', 'lost', 'WITH', 'lost.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\event', 'event', 'WITH', 'event.id = entry.event')
            ->where($qb5->expr()->orX(
                        $qb5->expr()->andX(
                            $qb5->expr()->isNotNull('gen.general_description'),
                            $qb5->expr()->isNull('gen.general_entry_closed_time')
                             ),                            
                        $qb5->expr()->andX(
                            $qb5->expr()->isNotNull('sec.security_description'),
                            $qb5->expr()->isNull('sec.security_entry_closed_time')
                             ),
                        $qb5->expr()->andX(
                            $qb5->expr()->isNotNull('med.medical_description'),
                            $qb5->expr()->isNull('med.medical_entry_closed_time')
                            ),
                        $qb5->expr()->andX(
                            $qb5->expr()->isNotNull('lost.lost_property_description'),
                            $qb5->expr()->isNull('lost.lost_property_entry_closed_time')
                            )))
            ->andWhere('event.id = :eventId')
            ->setParameter('eventId', $eventId)
            ;

        $logs['Open'] = $qb5->getQuery()->getSingleScalarResult();
        
        if ($logs)
        {
            $response = new JsonResponse();
            $response->setData($logs);
        }
        return $response;
    }
}


