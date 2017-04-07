<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\ControlRoomLED;
use AppBundle\Entity\venue;
use AppBundle\Entity\UPS_Status;
use AppBundle\Entity\UPS;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Description of LEDController
 *
 * @author Nick
 */
class LEDController extends Controller
{
    /**
     * @Route("/LED/status", name="LED_status");
     * 
     */
    public function LEDAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $led_statuses['Constant'] = $em->getRepository('AppBundle\Entity\ControlRoomLED')->getLatestLED();
        $people_counter_status = $em->getRepository('AppBundle\Entity\venue')->getpeoplecountingstatus();
        $UPS_statuses = $em->getRepository('AppBundle\Entity\UPS_Status')->getLatestUPS('*');
        
        $people_counter_status = true;
        
        if($people_counter_status == False)
        {
            $led_statuses['Alert']['Type'] = 'Flash';
            $led_statuses['Alert']['Colour'] = 'Red';
        } else {
            $led_statuses['Alert']['Type'] = 'None';
        }
        
        $max_UPS = 0;
        $tempUPSstatus = [0,0,0,0];
        
        if($UPS_statuses)
        {
            foreach ($UPS_statuses as $key => $ups)
            {
                if ($ups['status'] == 'Timeout')
                {
                    $tempUPSstatus[$key] = 1;
                } elseif ($ups['status'] == 'Battery')
                {
                    $tempUPSstatus[$key] = 2;
                } elseif ($ups['status'] == 'Fault')
                {
                    $tempUPSstatus[$key] = 3;
                }
            }
        }
        
        $max_UPS = max($tempUPSstatus);
        
        if($max_UPS == 1)
        {
            $led_statuses['Alert']['Type'] = 'Flash';
            $led_statuses['Alert']['Colour'] = 'Green';
        } elseif ($max_UPS == 2) 
        {
            $led_statuses['Alert']['Type'] = 'Flash';
            $led_statuses['Alert']['Colour'] = 'Yellow';
        } elseif ($max_UPS == 3) 
        {
            $led_statuses['Alert']['Type'] = 'Flash';
            $led_statuses['Alert']['Colour'] = 'Red';
        }
        
        if ($led_statuses)
        {
                $response = new JsonResponse();
                $response->setData($led_statuses);
        } else {
            $response = new Response();
            $response->setContent('Hello World');
            $response->headers->set('Content-Type', 'text/plain');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        return $response;
    }
    
    /**
     * @Route("/LED/mode/{mode}", name="LED_mode");
     * 
     */
    public function LEDModeAction(Request $request, $mode=null)
    {
        $em = $this->getDoctrine()->getManager();
        $desk_led = new ControlRoomLED();
        $underDesk_led = new ControlRoomLED();
        $room_led = new ControlRoomLED();
        $user = $this->getUser();
        
        $desk_led->setOperator($user);
        $desk_led->setTimestamp();
        $desk_led->setledRef("Desk");
        
        $underDesk_led->setOperator($user);
        $underDesk_led->setTimestamp();
        $underDesk_led->setledRef("Under");
        
        $room_led->setOperator($user);
        $room_led->setTimestamp();
        $room_led->setledRef("Room");
        
        if ($mode == "Working")
        {
            $desk_led->setColour("White");
            $desk_led->setBrightness("1");
            $underDesk_led->setColour("White");
            $underDesk_led->setBrightness("1");
            $room_led->setColour("White");
            $room_led->setBrightness("1");
        }
        elseif ($mode == "Control")
        {
            $desk_led->setColour("White");
            $desk_led->setBrightness("0.5");
            $underDesk_led->setColour("Blue");
            $underDesk_led->setBrightness("1");
            $room_led->setColour("White");
            $room_led->setBrightness("0.5");
        }
        elseif ($mode == "Off")
        {
            $desk_led->setColour("White");
            $desk_led->setBrightness("0");
            $underDesk_led->setColour("White");
            $underDesk_led->setBrightness("0");
            $room_led->setColour("White");
            $room_led->setBrightness("0");
        }
        
        $em->persist($desk_led);
        $em->persist($underDesk_led);
        $em->persist($room_led);
        $em->flush();
        
        $response = new Response('LED updated',Response::HTTP_OK, array('content-type' => 'text/html'));

        return $response;
    }
}
