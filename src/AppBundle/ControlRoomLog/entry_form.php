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
    
    public function entryAction(Request $request)
    {
        $new_entry = new log_entries();
//        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
//            throw $this->createAccessDeniedException();
//        }
        $em = $this->getDoctrine()->getManager();
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        $event = $usr->getSelectedEvent();
        $em->flush();

        $user = $this->getUser();

        $new_entry->setLogEntryOpenTime(new \DateTime());
        $new_entry->setOperator($user);

        
        $form = $this->createForm(LogType::class, $new_entry, array(
            'method' => 'POST',
        ));
        
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
        
            if ($form->isValid()) {
                //$em = $this->getDoctrine()->getManager();
                $new_entry->setLogTimestamp(new \DateTime());
                $new_entry->setEvent($event);
                
                //Lookup log location in event location entity and if latlong is blank use data
                if($form['longitude']->getData() == null){
                    $log_location = $form['location']->getData();
                    $location = $em->getRepository('AppBundle\Entity\Locations')->findOneBy(array('event' => $event, 'locationText' => $log_location));
                    if($location){
                        $latLong = explode(", ", $location->getLocationLatLong());
                        $new_entry->setLatitude($latLong[0]);
                        $new_entry->setLongitude($latLong[1]);
                    }
                    $new_entry->setGeolocated(false);
                } else{
                    $new_entry->setGeolocated(true);
                }
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


