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
        
        $firebaseID = $request->request->get('firebaseid');
        $username = $request->request->get('username');
        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');
        $response->setContent('failure');
        
        if ($lookup_key == $key){
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle\Entity\User')->findOneBy(array('username' => $username));
            if ($user){
                $user->setFirebaseID($firebaseID);
                $em->persist($user);
                $em->flush();
                $response->setContent('success');
            }
        
        }
        $response->send();
        return $response;
    }
}

