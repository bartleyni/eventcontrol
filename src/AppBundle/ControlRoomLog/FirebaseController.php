<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\ControlRoomLog;

use AppBundle\Form\Type\UserType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of FirebaseController
 *
 * @author Nick
 */
class FirebaseController extends Controller {
    /**
     * @Route("/firebase/register/{key}", name="firebase_registration")
     */
    public function firebaseRegisterAction($key, Request $request)
    {
        $lookup_key = $this->getParameter('pc_key');
        
        $firebaseID = $request->request->get('token');
        $username = $request->request->get('username');

        
        
        if ($lookup_key == $key){
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle\Entity\User')->findOneBy(array('username' => $username));
            if ($user){
                $user->setFirebaseID($firebaseID);
                $em->persist($user);
                $em->flush();
                $response = new JsonResponse(array('error' => false, 'message' => "Token stored"));
            } else {
                $response = new JsonResponse(array('error' => true, 'message' => "Username does not exist"));
            }
        
        } else {
            $response = new JsonResponse(array('error' => true, 'message' => "Authorisation Failure"));
        }
        //$response->send();
        return $response;
    }
}

