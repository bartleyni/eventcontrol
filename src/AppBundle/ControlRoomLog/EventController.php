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
    * @Route("/event/edit/{editId}", name="edit_event");
    * @Route("/event/activate/{activateId}", name="activate_event");
    * @Route("/event/delete/{deleteId}", name="delete_event");
    * @Route("/event/{filter}", name="filter_event_list");
    * @Route("/event/", name="event_list");
    */
    
    public function eventAction(Request $request, $editId=null, $deleteId=null, $filter=null, $activateId=null)
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
        
        if ($editId){
            //Do the edit thing
        }
        
        if ($deleteId){
            //Do the delete thing
        }
        
        if ($filter){
            if ($filter == "new"){
                //Do the new thing
            } else {
                //Do the filter thing
            }
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
}


