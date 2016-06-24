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
        
        $led_statuses = $em->getRepository('AppBundle\Entity\ControlRoomLED')->getLatestLED();
        
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
        $user = $this->getUser();
        $desk_led->setOperator($user);
        $desk_led->setTimestamp();
        $desk_led->setledRef("Desk");
        
        if ($mode == "Working")
        {
            $desk_led->setColour("White");
            $desk_led->setBrightness("1");
        }
        elseif ($mode == "Control")
        {
            $desk_led->setColour("White");
            $desk_led->setBrightness("0.5");
        }
        elseif ($mode == "Off")
        {
            $desk_led->setColour("White");
            $desk_led->setBrightness("0");
        }
        
        $em->persist($desk_led);
        $em->flush();
    }
}
