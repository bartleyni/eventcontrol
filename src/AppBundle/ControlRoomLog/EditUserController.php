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
use AppBundle\Form\Model\ChangePassword;
use AppBundle\Entity\User;

class EditUserController extends Controller
{
    /**
     * @Route("/user/", name="user_update")
     */
    public function editUserAction(Request $request)
    {
        $changePasswordModel = new ChangePassword();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createForm(new EditUserType(), $user, array(
            'method' => 'POST',
        ));
        
        $request = $this->getRequest();

        if ($form->isSubmitted() && $form->isValid()) {
            // perform some action,
            // such as encoding with MessageDigestPasswordEncoder and persist
            return $this->redirect($this->generateUrl('change_passwd_success'));
        }


        return $this->render('userEdit.html.twig', array('form' => $form->createView(),));      
    }
}
