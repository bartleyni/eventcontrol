<?php

namespace AppBundle\ControlRoomLog;

use AppBundle\Entity\camera;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CameraController extends Controller
{
    /**
     * @Route("/peoplecounting/newdata/{camera_id}/{venue_id}/{count}/", name="newdata")
     */
    public function newdataAction($camera_id, $venue_id, $count)
    {
        if ($camera_id and $venue_id and $count) {

            $camera = new camera();
            $camera->setCameraId(1);
            $camera->setCount(30);
            $camera->getVenue(1);

            $em = $this->getDoctrine()->getManager();
            $em->persist($camera);
            $em->flush();

            return new Response('Saved new camera data with id '.$camera->getId());
        }
    }
}