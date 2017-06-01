<?php

namespace AppBundle\ControlRoomLog;
use AppBundle\Entity\venue;
use AppBundle\Entity\camera_count;
use AppBundle\Entity\venue_camera;
use AppBundle\Entity\venue_event;
use AppBundle\Entity\VenueCountAlerts;
use AppBundle\Entity\skew;
use AppBundle\Entity\venue_cameraRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;
use AppBundle\Form\Type;
use AppBundle\Form\Type\SkewType;
use AppBundle\Form\Type\CountAlertType;
use AppBundle\Form\Type\VenueCameraType;
use AppBundle\Entity\VenueCountAlertsRepository;

class VenueController extends Controller
{
    /**
     * @Route("/peoplecounting", name="peoplecounting");
     *
     */
    public function view(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        $venue_event = $em->getRepository('AppBundle\Entity\venue')->getactiveeventvenues($usr);

        //echo $venue->getName();

        foreach ($venue_event as $key => $value) {
            $venue_event[$key]['count'] = $em->getRepository('AppBundle\Entity\venue')->getvenuecount($value['venue_id']['id'], $value['event_id']['event_log_stop_date'], $value['doors']);
        }

        return $this->render('peoplecounting.html.twig', array('venues' => $venue_event));
    }
    /**
     * @Route("/venue/detailed/{id}", name="venue_detailed");
     *
     */
    public function venue_detailed(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        $active_event = $usr->getSelectedEvent();
        $active_event_end_time=$active_event->getEventLogStopDate();
        $venue = $em->getRepository('AppBundle\Entity\venue')->findOneBy(array('id' => $id));
        $venue_event = $em->getRepository('AppBundle\Entity\venue')->getactiveeventvenues($usr);

        $venue_camera = new venue_camera();
        $venue_camera->setVenueId($venue);


        $form = $this->createForm(VenueCameraType::class, $venue_camera);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 4) save the skew!
            $em = $this->getDoctrine()->getManager();

            $em->persist($venue_camera);
            $em->flush();

            $venue_camera = new venue_camera();
            $venue_camera->setVenueId($venue);
            $form = $this->createForm(VenueCameraType::class, $venue_camera);
            //return $this->redirectToRoute('skew', ['id' => $id]);
        }
        $skew = new skew();
        $venue = $em->getRepository('AppBundle\Entity\venue')->findOneBy(array('id' => $id));
        $skew->setVenueId($venue);
        $form_skew = $this->createForm(SkewType::class, $skew);

        // 2) handle the submit (will only happen on POST)
        $form_skew->handleRequest($request);
        if ($form_skew->isSubmitted() && $form_skew->isValid()) {

            // 4) save the skew!
            $em = $this->getDoctrine()->getManager();

            $em->persist($skew);
            $em->flush();

            $skew = new skew();
            $skew->setVenueId($venue);
            $form_skew = $this->createForm(SkewType::class, $skew);
            //return $this->redirectToRoute('skew', ['id' => $id]);
        }
        
        $countAlert = new VenueCountAlerts();
        $active_event = $usr->getSelectedEvent();
        $venue_event2 = $em->getRepository('AppBundle\Entity\venue_event')->findOneBy(array('venue_id' => $id, 'event_id' => $active_event));
        $countAlert->setVenueEvent($venue_event2);
        $form_count_alert = $this->createForm(CountAlertType::class, $countAlert);

        // 2) handle the submit (will only happen on POST)
        $form_count_alert->handleRequest($request);
        if ($form_count_alert->isSubmitted() && $form_count_alert->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($countAlert);
            $em->flush();
            $form_count_alert = $this->createForm(CountAlertType::class, $countAlert);
        }
        
        $em->flush();
        $timestamp = $em->getRepository('AppBundle\Entity\venue')->getvenuedoors($id, $active_event);
        $skews = $em->getRepository('AppBundle\Entity\skew')->getvenueskew($id, $timestamp);
        $countAlerts = $em->getRepository('AppBundle\Entity\VenueCountAlerts')->getVenueEventCountAlerts($venue_event2);
        $venue_detailed_count= $em->getRepository('AppBundle\Entity\venue')->getvenuedetailedcount($id, $active_event_end_time, $timestamp);
        return $this->render('venue_detailed.html.twig', array('venue' => $venue,'skews' => $skews, 'countAlerts' => $countAlerts, 'venue_detailed_count' => $venue_detailed_count, 'form' => $form->createView(), 'form_skew' => $form_skew->createView()));
    }

     /**
     * @Route("/venue/skew/{key}/{venueId}/{in}/{out}", name="venue_skew");
     *
     */
    public function venueSkew($key, $venueId, $in, $out)
    {   
        $lookup_key = $this->getParameter('pc_key');
        if ($lookup_key == $key){
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
        } else {
            $response = new Response();
            $response->setContent('Unauthorised Access');
            $response->headers->set('Content-Type', 'text/plain');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->send();
        }
        return $response;
    }
    
    /**
     * @Route("/venue/doors/{id}", name="venue_doors");
     *
     */
    public function doors($id)
    {
        $em = $this->getDoctrine()->getManager();
        $venue_event = $em->getRepository('AppBundle\Entity\venue_event')->find($id);
        $venue_event->setDoors(new \DateTime());
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice','Doors set');
        return $this->redirectToRoute('peoplecounting');
    }
    /**
     * @Route("/venue/jsondata", name="venue_json_data");
     *
     */
    public function venue_json_data()
    {
        $em = $this->getDoctrine()->getManager();
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        $venue_event = $em->getRepository('AppBundle\Entity\venue')->getactiveeventvenues($usr);

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
            $response->setContent('Hello World');
            $response->headers->set('Content-Type', 'text/plain');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->send();
        }

        return $response;
    }
    
    /**
     * @Route("/venue/event/{key}/{id}", name="venue_event_json_data");
     *
     */
    public function venue_event_json_data($key, $id)
    {
        $lookup_key = $this->getParameter('pc_key');
        if ($lookup_key == $key){
        
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

        } else {
                $response = new Response();
                $response->setContent('Unauthorised Access');
                $response->headers->set('Content-Type', 'text/plain');
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
                $response->send();
        }
        return $response;
    }

    /**
     * @Route("/venue/camera/delete/{id}", name="venue_camera_delete");
     */

    public function deletVenueCamera(Request $request, $id=null)
    {
        $em = $this->getDoctrine()->getManager();

        if ($id){
            $venue_camera  = $em->getRepository('AppBundle\Entity\venue_camera')->findOneBy(array('id' => $id));
            $venue_id = $venue_camera->getVenueId()->getId();
            if ($venue_camera) {
                $em->remove($venue_camera);
                $em->flush();
            }
        }
       return $this->redirectToRoute('venue_detailed',  array('id' => $venue_id));
    }

    /**
     * @Route("/peoplecounting/slack_occupancy", name="slack_peoplecounting_occupancy");
     */

    public function slackOccupancy()
    {
        $request = Request::createFromGlobals();
        $text= $request->request->get('text');

        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery("SELECT v.id, v.name FROM AppBundle\Entity\venue v WHERE v.name LIKE :name")->setParameter('name', $text);
        $venues = $query->getArrayResult();
        
        print_r($venues);
        
        $response_text = "this is that you said ".$text;

        $response = new Response($response_text,Response::HTTP_OK, array('content-type' => 'text/html'));

        return $response;
        
    }

}
