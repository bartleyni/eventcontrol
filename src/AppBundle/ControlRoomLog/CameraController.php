<?php

namespace AppBundle\ControlRoomLog;

use AppBundle\Entity\camera_count;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;

class CameraController extends Controller
{
    /**
     * @Route("/camera_count/newdata/{camera_id}/{count_in}/{count_out}", name="new_camera_data")
     */
    public function newdataAction($camera_id, $count_in, $count_out)
    {
        if ($camera_id and $count_in and $count_out) {
            
            $em = $this->getDoctrine()->getManager();
            $camera = $em->getRepository('AppBundle\Entity\camera')->findOneBy((array('id' => $camera_id)));
            
            $camera_count = new camera_count();
            
            $camera_count->setCameraId($camera);
            $camera_count->setCountIn($count_in);
            $camera_count->setCountOut($count_out);
                               
            $em->persist($camera_count);
            $em->flush();

            $response = new Response('Data added',Response::HTTP_OK, array('content-type' => 'text/html'));
            
            return $response;
        }
    }
}