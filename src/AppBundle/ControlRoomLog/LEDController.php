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
        
        $now = new \DateTime();
        
        $led_statuses = $em->getRepository('AppBundle\Entity\ControlRoomLED')->getLatestLED();
        
        if ($led_statuses)
        {
                $response = new JsonResponse();
                $response->setData($led_statuses);

        } else {
            $response->setContent('Hello World');
            $response->headers->set('Content-Type', 'text/plain');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        
        return $response;
    }
}
