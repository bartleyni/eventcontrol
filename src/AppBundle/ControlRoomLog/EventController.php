<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Form\Type\EventType;
use AppBundle\Entity\event;
use AppBundle\Entity\user_events;
use AppBundle\Entity\Alert;
use AppBundle\Entity\Queue;

class EventController extends Controller
{
    /**
    * @Route("/event/{filter}", name="filter_event_list");
    * @Route("/event/", name="event_list");
    */
    
    public function listEventAction(Request $request, $filter=null)
    {
        $em = $this->getDoctrine()->getManager();
        
        if ($filter){
            //filter
        } else {
            //Do the display thing
        }
        
        $now = new \DateTime();
        
        $qb1 = $em->createQueryBuilder(); 
        
        $qb1
            ->select('event.id, event.client, event.name, event.event_date, event.event_log_start_date, event.event_log_stop_date, event.event_report_filename, event.event_report_run_date')
            ->from('AppBundle\Entity\event', 'event')
            ->where('event.event_log_stop_date >= :nowDate')
            ->setParameter('nowDate', $now)
            ->orderBy('event.event_date', 'ASC')
            ;
        
        $query1 = $qb1->getQuery();
        $events1 = $query1->getResult();
        
        $qb2 = $em->createQueryBuilder(); 
        
        $qb2
            ->select('event.id, event.client, event.name, event.event_date, event.event_log_start_date, event.event_log_stop_date, event.event_report_filename, event.event_report_run_date')
            ->from('AppBundle\Entity\event', 'event')
            ->where('event.event_log_stop_date < :nowDate')
            ->setParameter('nowDate', $now)
            ->orderBy('event.event_date', 'DESC')
            ;
        
        $query2 = $qb2->getQuery();
        $events2= $query2->getResult();
        $events = array_merge($events1,$events2); 
        return $this->render('eventList.html.twig', array('events' => $events));
    }

    /**
    * @Route("/event/new/", name="new_event");
    */    
    
    public function newEventAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $event = new event();
        $form = $this->createForm(new EventType($this->getDoctrine()->getManager()), $event, array('event_id' => null,));
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($event);
            $em->flush();
            
            $event_operators = $form['event_operators']->getData();
            
            //$all_users = $em->getRepository('AppBundle\Entity\User');
            
            if($event_operators)
            {                
                foreach ($event_operators as $key => $operatorId)
                {
                    
                    $user_event = $em->getRepository('AppBundle\Entity\user_events')->findOneBy(array('User_id' => $operatorId, 'event_id' => $editId));
                    $user = $em->getRepository('AppBundle\Entity\User')->findOneBy(array('id' => $operatorId));
                    
                    if(!$user_event)
                    {
                        $user_event = new user_events();
                    }
                    
                    $user_event->setUserId($user);
                    $user_event->setEventId($event);
                    $em->persist($user_event);
                    $em->flush();
                }
            }

            return $this->redirectToRoute('event_list');
        }
        
        return $this->render(
            'eventForm.html.twig',
            array('form' => $form->createView())
        );
    }
 
    /**
    * @Route("/event/edit/{editId}", name="edit_event");
    */
    
    public function editEventAction(Request $request, $editId=null)
    {
        $em = $this->getDoctrine()->getManager();
        
        if ($editId){
            $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(array('id' => $editId));
            $em->flush();
            $qb = $em->createQueryBuilder(); 
            $qb
                ->select('User.username, User.id')
                ->from('AppBundle\Entity\User', 'User')
                ;
            
            $query = $qb->getQuery();
            $operators = $query->getResult();
            
            $em->flush();
            $form = $this->createForm(new EventType($this->getDoctrine()->getManager()), $event, array('event_id' => $editId,));
            $form->handleRequest($request);
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $event_operators = $form['event_operators']->getData();
            
            $all_users = $em->getRepository('AppBundle\Entity\User');
            
            if($event_operators)
            {                
                foreach ($event_operators as $key => $operatorId)
                {
                    
                    $user_event = $em->getRepository('AppBundle\Entity\user_events')->findOneBy(array('User_id' => $operatorId, 'event_id' => $editId));
                    $user = $em->getRepository('AppBundle\Entity\User')->findOneBy(array('id' => $operatorId));
                    
                    if(!$user_event)
                    {
                        $user_event = new user_events();
                    }
                    
                    $user_event->setUserId($user);
                    $user_event->setEventId($event);
                    $em->persist($user_event);
                    $em->flush();
                }
            }
            
            $not_operators = $em->getRepository('AppBundle\Entity\user_events')->getEventUsersNotInList($editId,$event_operators);
            
            if($not_operators)
            {                
                foreach ($not_operators as $not_operator)
                {
                    
                    //$user_event = $em->getRepository('AppBundle\Entity\user_events')->findOneBy(array('User_id' => $operatorId, 'event_id' => $editId,));
                    
                    $em->remove($not_operator);
                    $em->flush();
                }
            }
            
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_list');
        }
        
        return $this->render(
            'eventForm.html.twig',
            array('form' => $form->createView(), 'event' => $event)
        );
    }
    
    /**
    * @Route("/event/delete/{deleteId}", name="delete_event");
    */
    
    public function deletEventAction(Request $request, $deleteId=null)
    {
        $em = $this->getDoctrine()->getManager();
                
        if ($deleteId){
            $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(array('id' => $deleteId));
            if ($event) {
                $em->remove($event);
                $em->flush();
            }
        }
        
        return $this->redirectToRoute('event_list');
    }
    
    /**
     * @Route("/event/status/", name="event_status");
     * 
     */
    public function EventTestStatusAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        #$now = new \DateTime();
        
        //$statuses = $em->getRepository('AppBundle\Entity\user_events')->getEventUsers(2);
        //$statuses = $em->getRepository('AppBundle\Entity\user_events')->getEventUsersNotInList(6,array(2,3,));
        $event = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent(1);
        $statuses = $event->getId();
        
        if ($statuses)
        {
                $response = new JsonResponse();
                $response->setData($statuses);

        } else {
            $response->setContent('Hello World');
            $response->headers->set('Content-Type', 'text/plain');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        
        return $response;
    }
    
    /**
    * @Route("/event/weather/", name="event_weather");
    */
    
    public function eventWeatherAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $usr = $this->get('security.context')->getToken()->getUser();
        $operatorId = $usr->getId();
        
        $event = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        
        $now = new \DateTime();
        
        $last_weather_update = $event->getEventLastWeatherUpdate();
        
        if($last_weather_update){
            $interval1 = date_diff($last_weather_update, $now, TRUE);
                
            $minutes = $interval1->days * 24 * 60;
            $minutes += $interval1->h * 60;
            $minutes += $interval1->i;
        } else {
            $minutes = 4;
        }
        
        if($last_weather_update && $minutes < 2){
            $summary = $event->getEventLastWeather();
            
        } else {

            //$latlong = "51.379551,-2.325717";
            $latlong = $event->getEventLatLong();
            $url = 'https://api.darksky.net/forecast/9c4ec6b414ca6374999b6b88fbc44634/'.$latlong.'?units=uk2&exclude=hourly,daily';
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $content = curl_exec($ch);

            if ($content){
                $data = json_decode($content, true);
                $summary = $data['minutely']['summary'];
                if (!$summary){
                    $summary = $data['hourly']['summary'];
                }
                $warning = '';
                
                if ($data['alerts']){
                    
                    $last_weather_warning_update = $event->getEventLastWeatherWarningUpdate();
                    if($last_weather_warning_update){
                        $interval2 = date_diff($last_weather_warning_update, $now, TRUE);
    
                        $minutes2 = $interval2->days * 24 * 60;
                        $minutes2 += $interval2->h * 60;
                        $minutes2 += $interval2->i;
                    } else {
                        $minutes2 = 31;
                    }
                    foreach ($data['alerts'] as $key => $alert)
                    {
                        $warning = $warning.$alert['title'].'<br>';
                        //Add Alert to Alert Queue System
                        if($minutes2 > 30){
                            $alert = new Alert();
                            $alert->setTitle($alert['title']);
                            $alert->setMessage($alert['description']);
                            $alert->setURL($alert['uri']);
                            $alert->setType("warning");
                            $alert->setEvent($event);
                            $em->persist($alert);
                            $em->flush();

                            $alert_queue = new Queue();
                            $alert_queue->setAlert($alert);                  
                            $em->persist($alert_queue);
                            $em->flush();
                            
                            $warning = $warning.$alert['title'].'<br>';
                            $event->setEventLastWeatherWarningUpdate($now);
                        }
                    }
                }
                
                if($summary){
                    $event->setEventLastWeather($summary);
                    $event->setEventLastWeatherUpdate($now);
                    $event->setEventLastWeatherWarning($warning);
                    $em->persist($event);
                }
            }
        }
        
        $em->flush();
        
        $response = new Response($summary,Response::HTTP_OK, array('content-type' => 'text/html'));

        return $response;
        
    }
    
    /**
    * @Route("/event/weather/warning", name="event_weather_warning");
    */
    
    public function eventWeatherWarningAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        
        $em = $this->getDoctrine()->getManager();
        $usr = $this->get('security.context')->getToken()->getUser();
        $operatorId = $usr->getId();
        
        $event = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        
        $warning = '';
        $warning = $event->getEventLastWeatherWarning();
        
        $response = new Response($warning,Response::HTTP_OK, array('content-type' => 'text/html'));

        return $response;
    }
    
    /**
    * @Route("/event/weather/radar", name="event_weather_radar");
    */
    
    public function eventWeatherRadarAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        
        $em = $this->getDoctrine()->getManager();
        $usr = $this->get('security.context')->getToken()->getUser();
        $operatorId = $usr->getId();
        
        $iframe = '<iframe id="iframe_radar" name="iframe_radar" src="//premium.raintoday.co.uk/mobile" frameborder=0 scrolling=no height="600px" class="col-md-12 embed-responsive-item" ></iframe>';
        $data = "u: weather@nb221.com p: uM7qflPqD91W";
        $username = "weather@nb221.com";
        $password = "uM7qflPqD91W";
        
        return $this->render('iframe.html.twig', array('iframe' => $iframe, 'iframe_username' => $username, 'iframe_password' => $password, 'data' => $data));
    }
}


