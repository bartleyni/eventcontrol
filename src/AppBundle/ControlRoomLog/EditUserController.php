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
use AppBundle\Form\Type\UpdateUserType;
use AppBundle\Entity\User;

class EditUserController extends Controller
{
    /**
     * @Route("/mySettings/", name="user_update")
     */
    public function updateMyUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        $form = $this->createForm(new UpdateUserType(), $user, array(
            'method' => 'POST',
        ));
        
        $request = $this->getRequest();
        
        $form->handleRequest($request);

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


        return $this->render('userUpdate.html.twig', array('form' => $form->createView(),));      
    }
    
    /**
    * @Route("/user");
    * @Route("/user/", name="user_list");
    */
    
    public function listUserAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $users = $em->getRepository('AppBundle\Entity\User')->findAll();
        return $this->render('userList.html.twig', array('users' => $users));
    }
    
    /**
     * @Route("/user/edit/{id}", name="user_edit")
     */
    public function editUserAction(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository('AppBundle\Entity\User')->findOneBy(array('id' => $id));
        
        $form = $this->createForm(new EditUserType(), $user, array(
            'method' => 'POST',
        ));
        
        $request = $this->getRequest();
        
        $form->handleRequest($request);

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

            return $this->redirectToRoute('user_list');
        }


        return $this->render('userEdit.html.twig', array('form' => $form->createView(),));      
    }
    
    
    /**
     * @Route("/user/fieldUser", name="field_user_toggle")
     */
    public function fieldUserToggleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        $group = $em->getRepository('AppBundle\Entity\Group')->findOneBy(array('name' => "Field User"));
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FIELD')) {
            $group->removeUser($user);
        } else {
            $group->addUser($user);
        }
        
        $em->persist($group);
        $em->flush();

        return $this->redirectToRoute('full_log');
   
    }
}
