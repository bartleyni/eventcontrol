<?php

namespace AppBundle\ControlRoomLog;

use AppBundle\Entity\camera;
use AppBundle\Entity\venue;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CameraController extends Controller
{
    /**
     * @Route("/camera/newdata/{camera_id}/{venue_id}/{count}/", name="newdata")
     */
    public function newdataAction($camera_id, $venue_id, $count)
    {
        if ($camera_id and $venue_id and $count) {
            
            $em = $this->getDoctrine()->getManager();
            
            $camera = new camera();
            $camera->setCameraId($camera_id);
            $camera->setCount($count);
            //$venue = new venue();
            //$venue->setName("test")'
            $venue = $em->getRepository('AppBundle\Entity\event')->findOneBy(array('id' => $venue_id));
            
            $camera->setVenue($venue);
            $em->persist($camera);
            $em->flush();

            return new Response('Saved new camera data with id '.$camera->getId());
        }
    }
}