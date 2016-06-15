<?php

namespace AppBundle\ControlRoomLog;

use AppBundle\Entity\camera;
use AppBundle\Entity\venue;
use AppBundle\Entity\venue_camera;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;

class CameraController extends Controller
{
    /**
     * @Route("/camera/newdata/{camera_id}/{count_in}/{count_out}", name="new_camera_data")
     */
    public function newdataAction($camera_id, $count_in, $count_out)
    {
        if ($camera_id and $count_in and $count_out) {
            
            $em = $this->getDoctrine()->getManager();
            
            $camera = new camera();
            
            $camera->setCameraId($camera_id);
            $camera->setCountIn($count_in);
            $camera->setCountOut($count_out);
                               
            $em->persist($camera);
            $em->flush();

            $response->setContent('');
            $response->headers->set('Content-Type', 'text/plain');
            $response->setStatusCode(Response::HTTP_OK);

            return $response;
        }
    }
}