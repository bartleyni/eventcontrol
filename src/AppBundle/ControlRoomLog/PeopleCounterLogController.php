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


        // Chart
        $series = array(
            array("name" => "Data Serie Name",    "data" => array(1,2,4,5,6,3,8))
        );

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
    }

}
