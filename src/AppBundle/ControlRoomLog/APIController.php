<?php

namespace AppBundle\ControlRoomLog;

use AppBundle\Entity\venue_event;
use AppBundle\Entity\Locations;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class APIController extends Controller
{
    
    /**
    * @Route("/api/events", name="api_events")
     * 
     * @param Request $request
     * @param type $filter
     * @return type
     */
    
    public function apiEventListAction(Request $request)
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        
        $em = $this->getDoctrine()->getManager();
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        $active_events = $em->getRepository('AppBundle\Entity\User')->getActiveEvents($usr);

        $json_data = $active_events;
        
        if ($json_data)
        {
                $response = new JsonResponse();
                $response->setData($json_data);

        } else {
            $response->setContent('No Events');
            $response->headers->set('Content-Type', 'text/plain');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        
        return $response;

    }
    
    /**
     * @Route("/api/ups/status/{event_id}", name="api_ups_status");
     * 
     */
    public function apiUPSstatusAction($event_id = null)
    {
            $em = $this->getDoctrine()->getManager();
            $ups_statuses = $em->getRepository('AppBundle\Entity\UPS_Status')->getLatestUPS($event_id);
            if ($ups_statuses)
            {
                    $response = new JsonResponse();
                    $response->setData($ups_statuses);
            } else {
                $response->setContent('No Status');
                $response->headers->set('Content-Type', 'text/plain');
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
            }
        
        return $response;
    }
    
    /**
     * @Route("/api/venue/count/{id}", name="api_venue_count");
     *
     */
    public function apiVenueCountAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $venue_event = $em->getRepository('AppBundle\Entity\venue')->getEventVenues($id);
        foreach ($venue_event as $key => $value) {
            $venues[$key]['id'] = $value['venue_id']['id'];
            $venues[$key]['name'] = $value['venue_id']['name'];
            $venues[$key]['count'] = $em->getRepository('AppBundle\Entity\venue')->getvenuecount($value['venue_id']['id'], $value['event_id']['event_log_stop_date'], $value['doors']);
            $status = $em->getRepository('AppBundle\Entity\venue')->getvenuestatus($value['venue_id']['id']);
            if ($status) {   $venues[$key]['status'] = "true"; }else{  $venues[$key]['status'] = "false"; }
            $status = $em->getRepository('AppBundle\Entity\venue')->getpeoplecountingstatus();
            if ($status) {   $venues['people_counting_status'] = "true"; }else{  $venues['people_counting_status'] = "false"; }
        }
        if ($venues)
        {
            $response = new JsonResponse();
            $response->setData($venues);
        } else {
            $response = new Response();
            $response->setContent('No data');
            $response->headers->set('Content-Type', 'text/plain');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->send();
        }
        return $response;
    }

    /**
     * @Route("/api/venue/skew/{venueId}/{in}/{out}", name="api_venue_skew");
     *
     */
    public function apiVenueSkewAction($venueId, $in, $out)
    {   
        $em = $this->getDoctrine()->getManager();
        $venue = $em->getRepository('AppBundle\Entity\venue')->findOneBy(array('id' => $venueId));
        $skew = new skew();
        $skew->setVenueId($venue);
        $skew->setSkewIn($in);
        $skew->setSkewOut($out);
        $em->persist($skew);
        $em->flush();

        $response = new Response();
        $response->setContent('Updated');
        $response->headers->set('Content-Type', 'text/plain');
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        $response->send();
        
        return $response;
    }
}
