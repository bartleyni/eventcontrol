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
        
        $usr = $this->get('security.context')->getToken()->getUser();
        $operatorId = $usr->getId();
        
        $event = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        
        $em->flush();
        
        if ($event)
        {
            $eventId = $event->getId();
        } else {
            $eventId = 0;
        }
        
        $em->flush();
        
        $qb = $em->createQueryBuilder(); 
        
        $qb
            ->select('entry.id, entry.log_entry_open_time, entry.log_update_timestamp, entry.log_blurb, entry.location, entry.reported_by, entry.park_alert, gen.general_description, gen.general_open, gen.general_entry_closed_time, sec.security_description, sec.security_entry_closed_time, secinc.security_incident_description, secinc.severity, secinc.security_incident_colour, med.medical_entry_closed_time, medinj.medical_severity, medinj.medical_injury_description, medrsp.medical_response_description, med.nine_nine_nine_required, lost.lost_property_entry_closed_time, lost.lost_property_description')
            ->from('AppBundle\Entity\log_entries', 'entry')
            ->orderBy('entry.log_entry_open_time', $sort_dir)
            ->leftJoin('AppBundle\Entity\general_log', 'gen', 'WITH', 'gen.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\security_log', 'sec', 'WITH', 'sec.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\security_incident_type', 'secinc', 'WITH', 'secinc.id = sec.security_incident_type')
            ->leftJoin('AppBundle\Entity\medical_log', 'med', 'WITH', 'med.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\medical_reported_injury_type', 'medinj', 'WITH', 'medinj.id = med.medical_reported_injury_type')
            ->leftJoin('AppBundle\Entity\medical_response', 'medrsp', 'WITH', 'medrsp.id = med.medical_response')
            ->leftJoin('AppBundle\Entity\lost_property', 'lost', 'WITH', 'lost.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\User', 'user', 'WITH', 'user.id = entry.operator')
            ->leftJoin('AppBundle\Entity\event', 'event', 'WITH', 'event.id = entry.event')
            ->where('event.id = :eventId')
            ->setParameter('eventId', $eventId)
            ;
        
        if ($filter_type=="medical"){
            if ($filter=="open"){
                $qb->andWhere($qb->expr()->andX(
                            $qb->expr()->isNotNull('med.medical_description'),
                            $qb->expr()->isNull('med.medical_entry_closed_time')
                        ));
            } elseif ($filter=="closed"){
                $qb->andWhere($qb->expr()->andX(
                            $qb->expr()->isNotNull('med.medical_description'),
                            $qb->expr()->isNotNull('med.medical_entry_closed_time')
                        ));
            } else {
                $qb->andWhere($qb->expr()->isNotNull('med.medical_description'));
            }
        } elseif ($filter_type=="security"){
            if ($filter=="open"){
                $qb->andWhere($qb->expr()->andX(
                            $qb->expr()->isNotNull('sec.security_description'),
                            $qb->expr()->isNull('sec.security_entry_closed_time')
                        ));
            } elseif ($filter=="closed"){
                $qb->andWhere($qb->expr()->andX(
                            $qb->expr()->isNotNull('sec.security_description'),
                            $qb->expr()->isNotNull('sec.security_entry_closed_time')
                        ));
            } else {
                $qb->andWhere($qb->expr()->isNotNull('sec.security_description'));
            }
        } elseif ($filter_type=="general"){
            if ($filter=="open"){
                $qb->andWhere($qb->expr()->andX(
                            $qb->expr()->isNotNull('gen.general_description'),
                            $qb->expr()->isNull('gen.general_entry_closed_time')
                        ) );
            } elseif ($filter=="closed"){
                $qb->andWhere($qb->expr()->andX(
                            $qb->expr()->isNotNull('gen.general_description'),
                            $qb->expr()->isNotNull('gen.general_entry_closed_time')
                        ));
            } else {
                $qb->andWhere($qb->expr()->isNotNull('gen.general_description'));
            }
        } elseif ($filter_type=="lost"){
            if ($filter=="open"){
                $qb
                    ->andWhere($qb->expr()->andX(
                            $qb->expr()->isNotNull('lost.lost_property_description'),
                            $qb->expr()->isNull('lost.lost_property_entry_closed_time')
                        ));
            } elseif ($filter=="closed"){
                $qb->andWhere($qb->expr()->andX(
                           $qb->expr()->isNotNull('lost.lost_property_description'),
                           $qb->expr()->isNotNull('lost.lost_property_entry_closed_time')
                        ));

            } else {
                $qb->andWhere($qb->expr()->isNotNull('lost.lost_property_description'));
            }
        }else{
            if ($filter == "open" && $filter_type == null){
                $qb->andWhere($qb->expr()->orX(
                        $qb->expr()->andX(
                            $qb->expr()->isNotNull('gen.general_description'),
                            $qb->expr()->isNull('gen.general_entry_closed_time')
                             ),                            
                        $qb->expr()->andX(
                            $qb->expr()->isNotNull('sec.security_description'),
                            $qb->expr()->isNull('sec.security_entry_closed_time')
                             ),
                        $qb->expr()->andX(
                            $qb->expr()->isNotNull('med.medical_description'),
                            $qb->expr()->isNull('med.medical_entry_closed_time')
                            ),
                        $qb->expr()->andX(
                            $qb->expr()->isNotNull('lost.lost_property_description'),
                            $qb->expr()->isNull('lost.lost_property_entry_closed_time')
                            )));
            } elseif ($filter=="closed" && $filter_type == null){
                $qb->andWhere($qb->expr()->andX(
                        $qb->expr()->orX(   
                            $qb->expr()->isNull('gen.general_description'),
                            $qb->expr()->andX(
                                $qb->expr()->isNotNull('gen.general_description'),
                                $qb->expr()->isNotNull('gen.general_entry_closed_time')
                                 )
                            ),
                        $qb->expr()->orX(   
                            $qb->expr()->isNull('lost.lost_property_description'),
                            $qb->expr()->andX(
                                $qb->expr()->isNotNull('lost.lost_property_description'),
                                $qb->expr()->isNotNull('lost.lost_property_entry_closed_time')
                                 )
                            ),                             
                        $qb->expr()->orX(   
                            $qb->expr()->isNull('sec.security_description'),
                            $qb->expr()->andX(
                                $qb->expr()->isNotNull('sec.security_description'),
                                $qb->expr()->isNotNull('sec.security_entry_closed_time')
                                 )
                            ),
                        $qb->expr()->orX(   
                            $qb->expr()->isNull('med.medical_description'),    
                            $qb->expr()->andX(
                                $qb->expr()->isNotNull('med.medical_description'),
                                $qb->expr()->isNotNull('med.medical_entry_closed_time')
                                ))));
            }
        }
        
        if ($event){
            //$begin = $event->getEventLogStartDate();
            //$end = $event->getEventLogStopDate();
            $eventId = $event->getId();
            
            $qb->andWhere('entry.event = :eventId')
                ->setParameter('eventId', $eventId);
            
            //$qb->andWhere('entry.log_entry_open_time <= :begin')
                //->andWhere('entry.log_entry_open_time >= :end')
                //->setParameter('begin', $begin)
                //->setParameter('end', $end);
        }else{
            $qb->andWhere('entry.log_entry_open_time <= :begin')
                ->andWhere('entry.log_entry_open_time >= :end')
                ->setParameter('begin', new \DateTime('2020-04-30'))
                ->setParameter('end', new \DateTime('2014-04-25'));
        }
        $query = $qb->getQuery();
        $logs = $query->getResult();
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
        $sort_dir = $sort == 'ASC' ? 'ASC' : 'DESC';
        $em = $this->getDoctrine()->getManager();
        
        $usr = $this->get('security.context')->getToken()->getUser();
        $operatorId = $usr->getId();
        
        //$user_event = $em->getRepository('AppBundle\Entity\user_events')->findOneBy(array('User_id' => $operatorId, 'active' => true));
        $event = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        $em->flush();
        
        if($user_event)
        {
            $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(
            array('id' => $eId));
            $eId = $user_event->getEventId();
        }
        
        //$event = $em->getRepository('AppBundle\Entity\event')->findOneBy(
            //array('id' => $eId));
        
        if ($event)
        {
            $eventId = $event->getId();
        } else {
            $eventId = 0;
        }
        
        $em->flush();
        
        $qb = $em->createQueryBuilder(); 
        
        $qb
            ->select('entry.id, entry.log_entry_open_time, entry.log_update_timestamp, entry.log_blurb, entry.location, entry.reported_by, entry.park_alert, gen.general_description, gen.general_open, gen.general_entry_closed_time, sec.security_description, sec.security_entry_closed_time, secinc.security_incident_description, secinc.severity, secinc.security_incident_colour, med.medical_entry_closed_time, medinj.medical_severity, medinj.medical_injury_description, medrsp.medical_response_description, med.nine_nine_nine_required, lost.lost_property_entry_closed_time, lost.lost_property_description')
            ->from('AppBundle\Entity\log_entries', 'entry')
            ->orderBy('entry.log_entry_open_time', $sort_dir)
            ->leftJoin('AppBundle\Entity\general_log', 'gen', 'WITH', 'gen.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\security_log', 'sec', 'WITH', 'sec.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\security_incident_type', 'secinc', 'WITH', 'secinc.id = sec.security_incident_type')
            ->leftJoin('AppBundle\Entity\medical_log', 'med', 'WITH', 'med.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\medical_reported_injury_type', 'medinj', 'WITH', 'medinj.id = med.medical_reported_injury_type')
            ->leftJoin('AppBundle\Entity\medical_response', 'medrsp', 'WITH', 'medrsp.id = med.medical_response')
            ->leftJoin('AppBundle\Entity\lost_property', 'lost', 'WITH', 'lost.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\User', 'user', 'WITH', 'user.id = entry.operator')
            ->leftJoin('AppBundle\Entity\event', 'event', 'WITH', 'event.id = entry.event')
            ->where('event.id = :eventId')
            ->setParameter('eventId', $eventId)
            ;
        
        if ($filter_type=="medical"){
            if ($filter=="open"){
                $qb->andWhere($qb->expr()->andX(
                            $qb->expr()->isNotNull('med.medical_description'),
                            $qb->expr()->isNull('med.medical_entry_closed_time')
                        ));
            } elseif ($filter=="closed"){
                $qb->andWhere($qb->expr()->andX(
                            $qb->expr()->isNotNull('med.medical_description'),
                            $qb->expr()->isNotNull('med.medical_entry_closed_time')
                        ));
            } else {
                $qb->andWhere($qb->expr()->isNotNull('med.medical_description'));
            }
        } elseif ($filter_type=="security"){
            if ($filter=="open"){
                $qb->andWhere($qb->expr()->andX(
                            $qb->expr()->isNotNull('sec.security_description'),
                            $qb->expr()->isNull('sec.security_entry_closed_time')
                        ));
            } elseif ($filter=="closed"){
                $qb->andWhere($qb->expr()->andX(
                            $qb->expr()->isNotNull('sec.security_description'),
                            $qb->expr()->isNotNull('sec.security_entry_closed_time')
                        ));
            } else {
                $qb->andWhere($qb->expr()->isNotNull('sec.security_description'));
            }
        } elseif ($filter_type=="general"){
            if ($filter=="open"){
                $qb->andWhere($qb->expr()->andX(
                            $qb->expr()->isNotNull('gen.general_description'),
                            $qb->expr()->isNull('gen.general_entry_closed_time')
                        ) );
            } elseif ($filter=="closed"){
                $qb->andWhere($qb->expr()->andX(
                            $qb->expr()->isNotNull('gen.general_description'),
                            $qb->expr()->isNotNull('gen.general_entry_closed_time')
                        ));
            } else {
                $qb->andWhere($qb->expr()->isNotNull('gen.general_description'));
            }
        } elseif ($filter_type=="lost"){
            if ($filter=="open"){
                $qb
                    ->andWhere($qb->expr()->andX(
                            $qb->expr()->isNotNull('lost.lost_property_description'),
                            $qb->expr()->isNull('lost.lost_property_entry_closed_time')
                        ));
            } elseif ($filter=="closed"){
                $qb->andWhere($qb->expr()->andX(
                           $qb->expr()->isNotNull('lost.lost_property_description'),
                           $qb->expr()->isNotNull('lost.lost_property_entry_closed_time')
                        ));

            } else {
                $qb->andWhere($qb->expr()->isNotNull('lost.lost_property_description'));
            }
        }else{
            if ($filter=="open"){
                $qb->andWhere($qb->expr()->orX(
                        $qb->expr()->andX(
                            $qb->expr()->isNotNull('gen.general_description'),
                            $qb->expr()->isNull('gen.general_entry_closed_time')
                             ),                            
                        $qb->expr()->andX(
                            $qb->expr()->isNotNull('sec.security_description'),
                            $qb->expr()->isNull('sec.security_entry_closed_time')
                             ),
                        $qb->expr()->andX(
                            $qb->expr()->isNotNull('med.medical_description'),
                            $qb->expr()->isNull('med.medical_entry_closed_time')
                            ),
                        $qb->expr()->andX(
                            $qb->expr()->isNotNull('lost.lost_property_description'),
                            $qb->expr()->isNull('lost.lost_property_entry_closed_time')
                            )));
            } elseif ($filter=="closed"){
                $qb->andWhere($qb->expr()->andX(
                        $qb->expr()->orX(   
                            $qb->expr()->isNull('gen.general_description'),
                            $qb->expr()->andX(
                                $qb->expr()->isNotNull('gen.general_description'),
                                $qb->expr()->isNotNull('gen.general_entry_closed_time')
                                 )
                            ),
                        $qb->expr()->orX(   
                            $qb->expr()->isNull('lost.lost_property_description'),
                            $qb->expr()->andX(
                                $qb->expr()->isNotNull('lost.lost_property_description'),
                                $qb->expr()->isNotNull('lost.lost_property_entry_closed_time')
                                 )
                            ),                             
                        $qb->expr()->orX(   
                            $qb->expr()->isNull('sec.security_description'),
                            $qb->expr()->andX(
                                $qb->expr()->isNotNull('sec.security_description'),
                                $qb->expr()->isNotNull('sec.security_entry_closed_time')
                                 )
                            ),
                        $qb->expr()->orX(   
                            $qb->expr()->isNull('med.medical_description'),    
                            $qb->expr()->andX(
                                $qb->expr()->isNotNull('med.medical_description'),
                                $qb->expr()->isNotNull('med.medical_entry_closed_time')
                                ))));
            }
        }
        
        if ($event){
            //$begin = $event->getEventLogStartDate();
            //$end = $event->getEventLogStopDate();
            
            //$qb->andWhere('entry.log_entry_open_time <= :begin')
                //->andWhere('entry.log_entry_open_time >= :end')
                //->setParameter('begin', $begin)
                //->setParameter('end', $end);
            
            $eventId = $event->getId();
            
            $qb->andWhere('entry.event = :eventId')
                ->setParameter('eventId', $eventId);
        }else{
            $qb->andWhere('entry.log_entry_open_time <= :begin')
                ->andWhere('entry.log_entry_open_time >= :end')
                ->setParameter('begin', new \DateTime('2020-04-30'))
                ->setParameter('end', new \DateTime('2014-04-25'));
        }
        $query = $qb->getQuery();
        //$logs = $query->getResult();
        
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
        
        $usr = $this->get('security.context')->getToken()->getUser();
        $operatorId = $usr->getId();
        $event = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        //$user_event = $em->getRepository('AppBundle\Entity\user_events')->findOneBy(array('User_id' => $operatorId, 'active' => true));
        $em->flush();
        
        if($user_event)
        {
            $eId = $user_event->getEventId();

            $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(
                array('id' => $eId));
        }
        
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


