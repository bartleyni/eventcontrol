<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );    
    }
    
    /**
     * @Route("/audit", name="audit")
     */
    
    public function AuditLogAction()
    {
        $em = $this->getDoctrine()->getManager();
        $auditLogs = $em->getRepository('AppBundle\Entity\AuditLog')->findAll();
        
        if (!$auditLogs)
        {
            $auditLogs = null;
        }
        
        return $this->render('auditLogs.html.twig', array('auditLogs' => $auditLogs));
    }
    
}