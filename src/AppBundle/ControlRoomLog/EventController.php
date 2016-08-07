<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\EventType;
use AppBundle\Entity\event;

class EventController extends Controller
{
    /**
    * @Route("/event/{filter}", name="filter_event_list");
    * @Route("/event/", name="event_list");
    */
    
    public function listEventAction(Request $request, $filter=null)
    {
        $em = $this->getDoctrine()->getManager();
        
        if ($filter){
            //filter
        } else {
            //Do the display thing
        }
        
        $qb = $em->createQueryBuilder(); 
        
        $qb
            ->select('event.id, event.client, event.name, event.event_date, event.event_log_start_date, event.event_log_stop_date, event.event_active')
            ->from('AppBundle\Entity\event', 'event')
            ->orderBy('event.event_date', 'DESC')
            ;
        
        $query = $qb->getQuery();
        $events = $query->getResult();
        return $this->render('eventList.html.twig', array('events' => $events));
    }

    /**
    * @Route("/event/new/", name="new_event");
    */    
    
    public function newEventAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $event = new event();
        $form = $this->createForm(new EventType(), $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_list');
        }
        
        return $this->render(
            'eventForm.html.twig',
            array('form' => $form->createView())
        );
    }
 
    /**
    * @Route("/event/edit/{editId}", name="edit_event");
    */
    
    public function editEventAction(Request $request, $editId=null)
    {
        $em = $this->getDoctrine()->getManager();
        
        if ($editId){
            //Do the edit thing
        }
    }
    
    /**
    * @Route("/event/delete/{deleteId}", name="delete_event");
    */
    
    public function deletEventAction(Request $request, $deleteId=null)
    {
        $em = $this->getDoctrine()->getManager();
                
        if ($deleteId){
            //Do the delete thing
        }    
    }

    /**
    * @Route("/event/activate/{activateId}", name="activate_event");
    */
    
    public function activateEventAction(Request $request, $activateId=null)
    {
        $em = $this->getDoctrine()->getManager();
        
        if ($activateId){
            //Do the activate thing
            $qb = $em->createQueryBuilder(); 
            
            $qb->update('AppBundle\Entity\event', 'event')
                ->set('event.event_active', 0)
                ->getQuery()
                ->execute();
            
            $em->flush();
            
            $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(array('id' => $activateId));
            $event->setEventActive(1);
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('event_list');
        }
    }
}


