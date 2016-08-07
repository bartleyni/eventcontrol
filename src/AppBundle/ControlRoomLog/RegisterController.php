<?php

namespace AppBundle\ControlRoomLog;

use AppBundle\Form\Type\RegisterType;
use AppBundle\Entity\event_control_register;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends Controller
{
    /**
     * @Route("/signin", name="signin")
     * @Route("/signout/{id}", name="signout")
     */
    public function registerAction(Request $request, $id=null)
    {
        if ($id){
            if ($id == "all"){
                $em = $this->getDoctrine()->getManager();
                $attendees = $em->getRepository('AppBundle\Entity\event_control_register')->findBy(
                    array('time_out' => null));
                if ($attendees){
                    foreach($attendees as $attendee){
                        $attendee->setTimeOut(new \DateTime());
                        $em->persist($attendee);
                        $em->flush();
                    }
                }
                return $this->redirectToRoute('fire_register');
            }else{
            
            $em = $this->getDoctrine()->getManager();
            $attendee = $em->getRepository('AppBundle\Entity\event_control_register')->find($id);
            $attendee->setTimeOut(new \DateTime());
            $em->persist($attendee);
            $em->flush();
            return $this->redirectToRoute('fire_register');
            }
        }else{
            // 1) build the form
            $attendee = new event_control_register();
            $form = $this->createForm(new RegisterType(), $attendee);

            // 2) handle the submit (will only happen on POST)
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                    
                $attendee->setTimeIn(new \DateTime());
                
                    $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(
                        array('event_active' => true));
        
                    if ($event)
                    {
                        $eventId = $event->getId();
                    } else {
                        $eventId = 0;
                    }
                    
                $attendee->setEvent($eventId);
                // 4) save the User!
                $em = $this->getDoctrine()->getManager();
                
                $em->persist($attendee);
                $em->flush();

                return $this->redirectToRoute('full_log');
            }

            return $this->render(
                'signin.html.twig',
                array('form' => $form->createView())
            );
        }
    }
}