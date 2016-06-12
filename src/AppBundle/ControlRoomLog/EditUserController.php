<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\ControlRoomLog;

/**
 * Description of EditUserController
 *
 * @author Nick
 */

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Type\EditUserType;
use AppBundle\Entity\User;

class EditUserController extends Controller
{
    /**
     * @Route("/user/", name="user_update")
     */
    public function editUserAction(Request $request)
    {
        //$user = new User();
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        $form = $this->createForm(new EditUserType(), $user, array(
            'method' => 'POST',
        ));
        
        $request = $this->getRequest();

        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            
            if ($user->getPlainPassword() != NULL){
                $user->setPassword($password);
            }
            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_update');
        }


        return $this->render('userEdit.html.twig', array('form' => $form->createView(),));      
    }
}
