<?php

namespace AppBundle\ControlRoomLog;

use AppBundle\Form\Type\RegisterType;
use AppBundle\Entity\event_control_register;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
                        
                        $attendee_name = $attendee->getName();
                        $attendee_time_out = $attendee->getTimeOut();
                        $email_address = $attendee->getEmail();
                        $heading = "Event Control Site Sign-out";

                        $message = \Swift_Message::newInstance()
                            ->setSubject('Control Room Sign-out')
                            ->setFrom('event.control@nb221.com')
                            ->setTo($email_address)
                            ->setBody(
                                $this->renderView(
                                    'emailFireRegister.html.twig',
                                        array('heading' => $heading,
                                            'name' => $attendee_name,
                                            'time_out' => $attendee_time_out
                                        )
                                    ),
                                'text/html'
                                )
                        ;
                $this->get('mailer')->send($message);
                        
                    }
                }
                return $this->redirectToRoute('fire_register');
            }else{
            
            $attendee = $em->getRepository('AppBundle\Entity\event_control_register')->find($id);
            $attendee->setTimeOut(new \DateTime());
            $em->persist($attendee);
            $em->flush();
            
                $attendee_name = $attendee->getName();
                $attendee_time_out = $attendee->getTimeOut();
                $email_address = $attendee->getEmail();
                $heading = "Event Control Site Sign-out";
                
                $message = \Swift_Message::newInstance()
                    ->setSubject('Control Room Sign-out')
                    ->setFrom('event.control@nb221.com')
                    ->setTo($email_address)
                    ->setBody(
                        $this->renderView(
                            'emailFireRegister.html.twig',
                                array('heading' => $heading,
                                    'name' => $attendee_name,
                                    'time_out' => $attendee_time_out
                                )
                            ),
                        'text/html'
                        )
                ;
                $this->get('mailer')->send($message);
            
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
                
                $attendee_name = $attendee->getName();
                $attendee_time_in = $attendee->getTimeIn();
                $email_address = $attendee->getEmail();
                $heading = "Event Control Site Sign-in";
                $hash = $attendee->getSignOutHash();
                $expire = $attendee->getSignOutHashExpire();
                
                $message = \Swift_Message::newInstance()
                    ->setSubject('Control Room Sign-in')
                    ->setFrom('event.control@nb221.com')
                    ->setTo($email_address)
                    ->setBody(
                        $this->renderView(
                            'emailFireRegister.html.twig',
                                array('heading' => $heading,
                                    'name' => $attendee_name,
                                    'time_in' => $attendee_time_in,
                                    'hash' => $hash,
                                    'expire' => $expire
                                )
                            ),
                        'text/html'
                        )
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
    
    /**
     * @Route("/so/{hash}", name="hash_signout")
     */
    public function hashSignOutAction(Request $request, $hash=null)
    {
        $em = $this->getDoctrine()->getManager();
        if ($hash){
            $attendee = $em->getRepository('AppBundle\Entity\event_control_register')->findOneBy(array('sign_out_hash' => $hash));
            if ($attendee){ 
                $expire = $attendee->getSignOutHashExpire();
                $now = new \DateTime();
                if ($expire > $now){
                    $sign_out = $attendee->getTimeOut();
                    if ($sign_out == NULL){
                        $attendee->setTimeOut(new \DateTime());
                        $em->persist($attendee);
                        $em->flush();

                        $attendee_name = $attendee->getName();
                        $attendee_time_out = $attendee->getTimeOut();
                        $email_address = $attendee->getEmail();
                        $heading = "Event Control Site Sign-out";

                        $message = \Swift_Message::newInstance()
                            ->setSubject('Control Room Sign-out')
                            ->setFrom('event.control@nb221.com')
                            ->setTo($email_address)
                            ->setBody(
                                $this->renderView(
                                    'emailFireRegister.html.twig',
                                        array('heading' => $heading,
                                            'name' => $attendee_name,
                                            'time_out' => $attendee_time_out
                                        )
                                    ),
                                'text/html'
                                )
                        ;
                        $this->get('mailer')->send($message);
                        $status = "Successfully signed-out";
                        
                    } else{
                        $status = "You have already been signed-out.";
                    }
                } else{
                    $status = "This sign-out link has expired";
                }
            } else{
                $status = "This sign-out link is not valid";
            }
        
        $heading = "Fire Register";
        //$response = new Response($status,Response::HTTP_OK, array('content-type' => 'text/html'));

        //return $response;
        return $this->render('status.html.twig', array('Heading' => $heading, 'Message' => $status));
        }
    }
}
