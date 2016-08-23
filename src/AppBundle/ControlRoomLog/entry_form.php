<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Form\Type\LogType;
use AppBundle\Entity\log_entries;
use AppBundle\Entity\general_log;
use AppBundle\Entity\medical_log;
use AppBundle\Entity\security_log;
use AppBundle\Entity\lost_property;
use AppBundle\Entity\event;


class entry_form extends Controller
{
    /**
    * @Route("/entry/", name="new_entry");
    * 
    */
    
    public function entryAction()
    {
        $new_entry = new log_entries();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $usr = $this->get('security.context')->getToken()->getUser();
        $operatorId = $usr->getId();
        
        //$user_event = $em->getRepository('AppBundle\Entity\user_events')->findOneBy(array('User_id' => $operatorId, 'active' => true));
        $event = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        $em->flush();
        
        if($user_event)
        {
            $eId = $user_event->getEventId();

            $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(
                array('id' => $eId));
            $em->flush();
        }


        $user = $this->getUser();
        
        //$new_entry->setLogTimestamp(new \DateTime());
        $new_entry->setLogEntryOpenTime(new \DateTime());
        $new_entry->setOperator($user);
        //$new_general = new general_log();
        //$new_lost_property = new lost_property();
        //$new_medical = new medical_log();
        //$new_security = new security_log();
        
        $form = $this->createForm(new LogType(), $new_entry, array(
            'method' => 'POST',
        ));
         
        //$form = $this->createForm(new LogType(), $new_entry);
        
        $request = $this->getRequest();
        
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
        
            if ($form->isValid()) {
                //$em = $this->getDoctrine()->getManager();
                $new_entry->setLogTimestamp(new \DateTime());
                $new_entry->setEvent($event);
                $em->persist($new_entry);
                $em->flush();
                return $this->redirect($this->generateUrl('edit_entry', array('id' => $new_entry->getId())));

                //return $this->get('session')->setFlash('notice', 'You have successfully added');
            }
        }
        else
        {
            //return $this->redirect('../log/');
        }
        return $this->render('form.html.twig', array('log_entry' => $form->createView(),));
    }
}


