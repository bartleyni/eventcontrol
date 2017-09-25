<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Ob\HighchartsBundle\Highcharts\Highstock;

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
        $plotLines = [];
        dump($event);
        dump($venues);
        
        foreach ($venues as $venue) {
            
            $data = [];
            $venueData = [];
            
            dump($venue['id']);
            
            $logStart = $event->getEventLogStartDate();
            $logDoors = $event->getEventDate();
            $now = new \DateTime();
            
            if($logDoors > $now)
            {
                $logTime = $logStart;
            } else {
                $logTime = $logDoors;
            }
            
            $qb = $em->createQueryBuilder(); 
            
            $qb
                ->select('countLogs.id, countLogs.timestamp, countLogs.running_in, countLogs.running_out')
                ->from('AppBundle\Entity\PeopleCounterLog', 'countLogs')
                ->where('countLogs.event = :event')
                ->andWhere('countLogs.venue = :venue')
                ->andWhere('countLogs.timestamp > :doors')
                ->setParameter('event', $eventId)
                ->setParameter('venue', $venue['id'])
                //->setParameter('doors', $event->getEventDate())
                ->setParameter('doors', $logTime)
                ->orderBy('countLogs.timestamp', 'ASC')
                ;

            $counts = $qb->getQuery()->getResult();

            foreach ($counts as $count){
                dump($count);
                array_push($data,array((int)$count['timestamp']->format('U')*1000,$count['running_in'] - $count['running_out']));
            }
            
            $venueName = $venue['name'];
            $venueDoors = (int)$venue['doors']->format('U')*1000;
            
            $venue_count = array("name" => $venueName, "data" => $data);
            if ($venueDoors){
                $plotLine = array('color' => '#FF0000', 'width' => 1, 'value' => $venueDoors, 'label' => array('text' => $venueName." Doors"));
                array_push($plotLines, $plotLine);
            }
            
            if ($data){
                array_push($series, $venue_count);
            }
        }


        if($series)
        {
            $ob = new Highchart();
            //$ob = new Highstock();
            $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
            $ob->title->text($event->getName());
            $ob->chart->zoomType('x');
            $ob->xAxis->title(array('text'  => "Time"));
            $ob->xAxis->type('datetime');
            //$xMin = (time()-(8*60*60))*1000;
            //$xMax = time()*1000;
            $xMin = 0;
            $xMax = 60;
            if ($plotLines){
                $ob->xAxis->plotLines($plotLines);
            }
            $ob->yAxis->title(array('text'  => "Total Number of People"));
            $ob->yAxis->softMin(0);
            $ob->yAxis->softMax(1000);
            $ob->series($series);

            return $this->render('::peopleCountingLog.html.twig', array(
                'chart' => $ob,
            ));
        } else {
            return $this->render('::peopleCountingLog.html.twig');
        }
        
    }

}
