<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RegisterDisplay extends Controller
{
    /**
    * @Route("/fireregister/", name="fire_register");
    */
    
    public function fireRegisterAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder(); 
        
        $qb
            ->select('attendee.id, attendee.name, attendee.phone, attendee.email, attendee.time_in, attendee.time_out')
            ->from('AppBundle\Entity\event_control_register', 'attendee')
            ->orderBy('attendee.time_in', 'ASC')
            ;
        
        $qb->andWhere('attendee.time_in <= :begin')
            ->andWhere('attendee.time_in >= :end')
            ->setParameter('begin', new \DateTime('2016-04-30'))
            ->setParameter('end', new \DateTime('2015-04-25'));
        
        $query = $qb->getQuery();
        $attendees = $query->setMaxResults(30)
                            ->getResult();
        return $this->render('fireRegister.html.twig', array('attendees' => $attendees));
    }
}


