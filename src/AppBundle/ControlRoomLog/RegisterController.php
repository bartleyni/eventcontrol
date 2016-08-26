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
        $em = $this->getDoctrine()->getManager();
        if ($id){
            if ($id == "all"){
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
            
            $attendee = $em->getRepository('AppBundle\Entity\event_control_register')->find($id);
            $attendee->setTimeOut(new \DateTime());
            $em->persist($attendee);
            $em->flush();
            return $this->redirectToRoute('fire_register');
            }
        }else{
            // 1) build the form
            
            $usr = $this->get('security.context')->getToken()->getUser();
            $operatorId = $usr->getId();
            
            $attendee = new event_control_register();
            $form = $this->createForm(new RegisterType(), $attendee);
            $event = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
            $em->flush();
            // 2) handle the submit (will only happen on POST)
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                
                $attendee->setTimeIn(new \DateTime());
                
                //$event = $em->getRepository('AppBundle\Entity\event')->findOneBy(
                    //array('event_active' => true));

        
                if ($event){
                    $attendee->setEvent($event);
                }
                // 4) save the User!
                
                $em->persist($attendee);
                $em->flush();
                
                $message = \Swift_Message::newInstance()
                    ->setSubject('Control Room Hello Email')
                    ->setFrom('event.control@nb221.com')
                    ->setTo('nick@nb221.com')
                    ->setBody('This is a test message')
                ;
                $this->get('mailer')->send($message);


                return $this->redirectToRoute('full_log');
            }

            return $this->render(
                'signin.html.twig',
                array('form' => $form->createView())
            );
        }
    }
}
