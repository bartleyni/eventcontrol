<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\log_entriesRepository;

/**
 * Description of MapController
 *
 * @author Nick
 */
class MapController extends Controller 
{
    /**
    * @Route("/map.{_format}", name="map_pdf");
    * @Route("/map/");
    * @Route("/map", name="full_map");
    * @Route("/map/{filter}", name="map_filter");
    * @Route("/map/{filter}/");
    * @Route("/map/{filter}/{filter_type}", name="map_filter_type");
    * @Route("/map/{filter}/{filter_type}/");
    */
    
     public function mapAction($_format="html", $filter=null, $filter_type=null)
    {
        $em = $this->getDoctrine()->getManager();
        
        $usr = $this->get('security.context')->getToken()->getUser();
        $operatorId = $usr->getId();
        
        $event = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        
        $overlay = $event->getOverlayImageName();
        
        $em->flush();
        
        if ($overlay)
        {
            $eventId = $event->getId();
            $latLong = $latLong = explode(",", $event->getEventLatLong());
            $latitude = $latLong[0];
            $longitude = $latLong[1];
            $NEbound = $event->getNorthEastBounds();
            $SWbound = $event->getSouthWestBounds();
            return $this->render('map.html.twig', array('event' => $event, 'overlayFileName' => $overlay, 'NEbound' => $NEbound,'SWbound' => $SWbound, 'latitude' => $latitude, 'longitude' => $longitude, 'filter' => $filter, 'filter_type' => $filter_type));
        } else {
            return $this->redirectToRoute('full_log');
        }
    }
    
     /**
     * @Route("/mapjsondata/");
     * @Route("/mapjsondata/{filter}", name="map_filter_json_data");
     * @Route("/mapjsondata/{filter}/");
     * @Route("/mapjsondata/{filter}/{filter_type}", name="map_filter_type_json_data");
     * @Route("/mapjsondata/{filter}/{filter_type}/");
     * @Route("/mapjsondata", name="map_json_data");
     *
     */
    public function map_json_data($filter=null, $filter_type=null)
    {
        $em = $this->getDoctrine()->getManager();
        
        $usr = $this->get('security.context')->getToken()->getUser();
        $operatorId = $usr->getId();
        
        $event = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        
        $em->flush();
        
        $sort = 'DESC';
        
        if ($event)
        {
            $eventId = $event->getId();
        } else {
            $eventId = 0;
        }
        
        $logs = $em->getRepository('AppBundle\Entity\log_entries')->getLogEntries($eventId, $sort, $filter, $filter_type);
        
        //Now convert the data from logs in to GeoJson formatting.
        $data['type'] = "FeatureCollection";
        $data['features'] = array();
        
        $markerId = 0;
        $markers = array();
        
        foreach ($logs as $log)
        {
            if($log['latitude'] != null)
            {
                
                $key = array_search(round($log['latitude'], 3).", ".round($log['longitude'], 4), array_column($markers, 'latlong'));
                
                if($key == null)
                {
                    $markerId = $markerId+1;
                    $markers[$markerId] = ['latlong' => round($log['latitude'], 3).", ".round($log['longitude'], 4), 'latitude' => $log['latitude'], 'longitude' => $log['longitude']];
                    $key = $markerId;
                    $markers[$key]['logs'] = array();
                }
                
                $current_severity = $markers[$key]['severity'];
                
                $status = null;
                $general_status = "Closed";
                $lost_property_status = "Closed";
                $medical_status = "Closed";
                $security_status = "Closed";
                $colour = "777";
                $zIndex = 10;
                $severity = 99;
                
                if($log['severity'] && $log['medical_severity'])
                {
                    $severity = min($log['severity'], $log['medical_severity']);
                } else if($log['severity'] && !$log['medical_severity']) {
                    $severity = $log['severity'];
                } else if($log['medical_severity'] && !$log['severity']) {
                    $severity = $log['medical_severity'];
                } else {
                    $zIndex = 10;
                }
                
                if (!$log['security_description'] == null && $log['security_entry_closed_time'] == null)
                {
                    $security_status = "Open";
                }
                if (!$log['medical_description'] = null && $log['medical_entry_closed_time'] == null)
                {
                    $medical_status = "Open";
                }
                if (!$log['general_description'] == null && $log['general_entry_closed_time'] == null)
                {
                    $general_status = "Open";
                    $colour = "337ab7";
                    $zIndex = 50;
                }
                if (!$log['lost_property_description'] == null && $log['lost_property_entry_closed_time'] == null)
                {
                    $lost_property_status = "Open";
                    $colour = "5bc0de";
                    $zIndex = 40;
                }
                
                if ($severity === 1){
                    $colour = "E50D00";
                    $zIndex = 100;
                } elseif($severity === 2) {
                    $colour = "DF7200";
                    $zIndex = 90;
                } elseif($severity === 3) {
                    $colour = "D9D100";
                    $zIndex = 80;
                } elseif($severity === 4) {
                    $colour = "7CD300";
                    $zIndex = 70;
                } elseif($severity === 5) {
                    $colour = "1CCE00";
                    $zIndex = 60;
                }                
                
                if ($security_status === "Open" or $medical_status === "Open" or $general_status === "Open" or $lost_property_status === "Open")
                {
                    $status = "Open";
                } else {
                    $status = "Closed";
                    $severity = 99;
                    $colour = "777";
                    $zIndex = 1;
                }
                
                if ($current_severity == null)
                {
                    $new_severity = $severity;
                }
                
                if ($current_severity > $severity)
                {
                    $new_severity = $severity;
                } else {
                    $new_severity = $current_severity;
                }
                
                array_push($markers[$key]['logs'], $log);
                $markers[$key]['severity'] = $new_severity;
                $markers[$key]['colour'] = $colour;
            }
        }
        
        if ($markers)
        {
            foreach ($markers as $marker)
            {
                $logFeature = ['type' => "Feature", 'properties' => ["marker" => $marker], 'geometry' => ["type" => "point", "coordinates" => [floatval($marker['longitude']), floatval($marker['latitude'])]]];
                array_push($data['features'],$logFeature);
            }
        }
        
        $response = new JsonResponse();
        
        if ($logs)
        {
            $response->setData($data);
        } else {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        return $response;
    }
}
