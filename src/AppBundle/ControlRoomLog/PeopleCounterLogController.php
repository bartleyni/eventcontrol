<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;
use Ob\HighchartsBundle\Highcharts\Highchart;

class PeopleCounterLogController extends Controller
{
    /**
     * @Route("/peoplecountinglog", name="people_counting_log");
     *
     */
    
    public function chartAction()
    {
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
        
        $venues = $em->getRepository('AppBundle\Entity\venue')->getEventsVenuesByEventId($eventId);
        
        $series = [];
        
        foreach ($venues as $venue) {
            
            $data = [];
            
            $qb = $em->createQueryBuilder(); 
            
            $qb
                ->select('countLogs.id, countLogs.timestamp, countLogs.running_in, countLogs.running_out')
                ->from('AppBundle\Entity\PeopleCounterLog', 'countLogs')
                ->where('countLogs.event = :event')
                ->andWhere('countLogs.venue = :venue')
                ->setParameter('event', $eventId)
                ->setParameter('venue', $venue['id'])
                ->orderBy('countLogs.timestamp', 'ASC')
                ;

            $counts = $qb->getQuery()->getResult();

            foreach ($counts as $count){
                array_push($data,array($count['timestamp']->format('U'),$count['running_in'] - $count['running_out']));
            }
            
            $venueName = $venue['name'];
            
            $venue_count = array("name" => $venueName, "data" => $data);
            
            if ($data){
                array_push($series, $venue_count);
            }
            
        }

        if($series)
        {
            $ob = new Highchart();
            $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
            $ob->title->text($event->getName().' - Venue Occupancy');
            $ob->chart->zoomType('x');
            $ob->xAxis->title(array('text'  => "Time"));
            $ob->xAxis->type('datetime');
            $ob->yAxis->title(array('text'  => "Total Number of People"));
            $ob->series($series);

            return $this->render('::peopleCountingLog.html.twig', array(
                'chart' => $ob
            ));
        } else {
            return $this->render('::peopleCountingLog.html.twig');
        }
        
    }

}
