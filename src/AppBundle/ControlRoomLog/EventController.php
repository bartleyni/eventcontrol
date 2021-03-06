<?php

namespace AppBundle\ControlRoomLog;

use AppBundle\Entity\venue_event;
use AppBundle\Entity\Locations;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\UploadFile;
use Symfony\Component\HttpFoundation\File\File;
use AppBundle\Form\Type\EventType;
use AppBundle\Entity\event;
use AppBundle\Entity\Alert;
use AppBundle\Entity\Queue;
use Doctrine\Common\Collections\ArrayCollection;

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
        $current_overlay = null;
        $form = $this->createForm(EventType::class, $event, array('event_id' => null));
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            
            $em->persist($event);
            $editId = $event->getId();
            $em->flush();

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
            
            $current_overlay = $event->getOverlayImageName();
            
            $originalLocations = new ArrayCollection();

            // Create an ArrayCollection of the current location objects in the database
            foreach ($event->getLocations() as $location) {
                $originalLocations->add($location);
            }
            
            $events = $em->getRepository('AppBundle\Entity\event')->findAll();
    
            $em->flush();
            
            $form = $this->createForm(EventType::class, $event, array('event_id' => $editId, 'em' => $em));
            $form->handleRequest($request);
        }

        if ($form->isSubmitted()) {

            $event_venues = $form['event_venues']->getData();

            if($event_venues)
            {
                foreach ($event_venues as $key => $venue_id)
                {

                    $venue_event = $em->getRepository('AppBundle\Entity\venue_event')->findOneBy(array('venue_id' => $venue_id, 'event_id' => $editId));
                    $venue = $em->getRepository('AppBundle\Entity\venue')->findOneBy(array('id' => $venue_id));

                    if(!$venue_event)
                    {
                        $venue_event = new venue_event();
                    }

                    $venue_event->setVenueId($venue);
                    $venue_event->setEventId($event);
                    $em->persist($venue_event);
                    $em->flush();
                }
            }

            $not_venues = $em->getRepository('AppBundle\Entity\venue_event')->getEventVenueNotInList($editId, $event_venues);

            if($not_venues)
            {
                foreach ($not_venues as $not_venue)
                {
                    $em->remove($not_venue);
                    $em->flush();
                }
            }

            // remove the relationship between the location and the Event
            foreach ($originalLocations as $location) {
                if (false === $event->getLocations()->contains($location)) {
                    // remove the Event from the Location
                    //$location->getEvent()->removeElement($event);

                    // if it was a many-to-one relationship, remove the relationship like this
                    // $tag->setTask(null);

                    //$em->persist($location);

                    // if you wanted to delete the Tag entirely, you can also do that
                    $em->remove($location);
                }
            }
            
            $locations = $form['locations']->getData();
            
            foreach ($locations as $location){
                $location->setEvent($event);
                $em->persist($location);
            }
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_list');
        }
        
        return $this->render(
            'eventForm.html.twig',
            array('form' => $form->createView(), 'event' => $event, 'current_overlay' => $current_overlay, 'events' => $events)
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
       
        $statuses = 1;
        
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
//        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
//            throw $this->createAccessDeniedException();
//        }
        
        $em = $this->getDoctrine()->getManager();    
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        $event = $usr->getSelectedEvent();      
        $now = new \DateTime();
        $icon = null;
        
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
            $icon = $event->getEventLastWeatherIcon();
        } else {
            $latlong = $event->getEventLatLong();
            $key = $this->getParameter('ds_key');
            $url = 'https://api.darksky.net/forecast/'.$key.'/'.$latlong.'?units=uk2&exclude=hourly,daily';
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $content = curl_exec($ch);

            if ($content){
                $data = json_decode($content, true);
                if (is_array($data)) {
                    $summary = $data['minutely']['summary'];
                    $icon = $data['currently']['icon'];
                    if (!$summary){
                        $summary = $data['hourly']['summary'];
                    }
                    $warning = '';
                
                    
                    if (array_key_exists('alerts', $data)) {   
                        $last_weather_warning_update = $event->getEventLastWeatherWarningUpdate();
                        $last_weather_warning = $event->getEventLastWeatherWarning();
                        if($last_weather_warning_update){
                            $interval2 = date_diff($last_weather_warning_update, $now, TRUE);

                            $minutes2 = $interval2->days * 24 * 60;
                            $minutes2 += $interval2->h * 60;
                            $minutes2 += $interval2->i;
                        } else {
                            $minutes2 = 31;
                        }

                        if($minutes2 > 30){
                            foreach ($data['alerts'] as $key => $WeatherAlert)
                            {
                                $warning = $warning.$WeatherAlert['title'].'<br>';
                            }
                            if ($warning != $last_weather_warning){

                                $event->setEventLastWeatherWarning($warning);
                                foreach ($data['alerts'] as $key => $WeatherAlert)
                                {
                                    $alert = new Alert();
                                    $alert->setTitle($WeatherAlert['title']);
                                    $alert->setMessage($WeatherAlert['description']);
                                    $alert->setURL($WeatherAlert['uri']);
                                    $alert->setType("warning");
                                    $alert->setFor("Weather");
                                    $alert->setEvent($event);
                                    $em->persist($alert);
                                    $em->flush();

                                    //Moved to alert listener
                                    //$alert_queue = new Queue();
                                    //$alert_queue->setAlert($alert);                  
                                    //$em->persist($alert_queue);
                                    //$em->flush(); 
                                }
                            }
                            $event->setEventLastWeatherWarningUpdate($now);
                            $em->persist($event);
                        }
                    } else {
                        $warning = '';
                        $event->setEventLastWeatherWarning($warning);
                        $event->setEventLastWeatherWarningUpdate($now);
                    }
                    if($summary) {
                        $event->setEventLastWeather($summary);
                        $event->setEventLastWeatherUpdate($now);
                        $em->persist($event);
                    }
                    if($icon) {
                        $event->setEventLastWeatherIcon($icon);
                        $em->persist($event);
                    }
                } else {
                    $summary = "No Weather Data";
                    $icon = null;
                }
            }
        }
        
        $em->flush();
        
        $weatherInfo = array("summary" => $summary, "icon" => $icon);
        
        $response = new JsonResponse();
        $response->setData($weatherInfo);
        
        //$response = new Response($summary, Response::HTTP_OK, array('content-type' => 'text/html'));

        return $response;
        
    }
    
    /**
    * @Route("/event/weather/warning", name="event_weather_warning");
    */
    
    public function eventWeatherWarningAction(Request $request)
    {
//        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
//            throw $this->createAccessDeniedException();
//        }
        $em = $this->getDoctrine()->getManager();
        $usr = $this->get('security.token_storage')->getToken()->getUser();

        $event = $usr->getSelectedEvent();
        
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
//        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
//            throw $this->createAccessDeniedException();
//        }
        
        $em = $this->getDoctrine()->getManager();
        $usr = $this->get('security.token_storage')->getToken()->getUser();

        $target = "http://premium.raintoday.co.uk/mobile";
        //$iframe = '<iframe id="iframe_radar" name="iframe_radar" src="http://premium.raintoday.co.uk/mobile" frameborder=0 scrolling=no height="600px" class="col-md-12 embed-responsive-item" ></iframe>';
        $iframe = '<iframe id="iframe" name="iframe_radar" src="" frameborder=0 scrolling=no height="600px" class="col-md-12 embed-responsive-item" ></iframe>';
        $iframeDynamic = 'http://premium.raintoday.co.uk/mobile';
        $data = "u: weather@nb221.com p:";
        $username = "weather@nb221.com";
        $password = "";
        
        return $this->render('iframe.html.twig', array('iframeDynamic' => $iframeDynamic, 'iframe' => $iframe, 'target' => $target, 'iframe_username' => $username, 'iframe_password' => $password, 'data' => $data));
    }
    
    /**
    * @Route("/event/locationCopy/{editId}/{copyId}", name="location_copy");
    */
    
    public function editEventLocationCopyAction($editId=null, $copyId=null)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(array('id' => $editId));
        $em->flush();
        $copyEvent = $em->getRepository('AppBundle\Entity\event')->findOneBy(array('id' => $copyId));
        $em->flush();
                
        $locations = $copyEvent->getLocations();

        foreach ($locations as $location){
            $newLocation = new Locations;
            $newLocation->setLocationLatLong($location->getLocationLatLong());
            $newLocation->setLocationText($location->getLocationText());
            $newLocation->setEvent($event);
            $event->addLocation($newLocation);
            $em->persist($newLocation);
            $em->persist($event);
            $em->flush();
        }
        
        return $this->redirectToRoute('edit_event', array('editId' => $editId));       
    }
     
}


