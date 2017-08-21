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
        $selected_event = $usr->getSelectedEvent();
        $active_events = $em->getRepository('AppBundle\Entity\User')->getActiveEvents($usr);

        if ($selected_event)
        {
                $response = new JsonResponse();
                $response->setData($active_events);

        } else {
            $response->setContent('No Events');
            $response->headers->set('Content-Type', 'text/plain');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        
        return $response;

    }
     
}


