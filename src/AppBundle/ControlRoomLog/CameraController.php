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

            $venue = $em->getRepository('AppBundle\Entity\venue_camera')->getcameravenue($camera_id);
            print_r($venue);
           
            print_r($em->getRepository('AppBundle\Entity\venue')->getvenuecount($venue['venue_id']));
          
           
            $em->persist($camera);
            $em->flush();

            //return new Response('Saved new camera data with id '.$camera->getId());
        }
    }

    /**
     * @Route("/camera/jsondata", name="People counting json data");
     *
     */
    public function json_data(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery("SELECT v FROM AppBundle\Entity\venue v");
        $data = $query->getArrayResult();

        print_r($data);
        if ($data)
        {
            $response = new JsonResponse();
            $response->setData($data);

        } else {
            $response->setContent('Hello World');
            $response->headers->set('Content-Type', 'text/plain');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return $response;


        //return $response;
    }

}