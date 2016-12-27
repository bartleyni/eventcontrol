<?php

namespace AppBundle\ControlRoomLog;
use AppBundle\Entity\venue;
use AppBundle\Entity\camera_count;
use AppBundle\Entity\venue_camera;
use AppBundle\Entity\skew;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;
use AppBundle\Form\Type\SkewType;

class VenueController extends Controller
{
    /**
     * @Route("/peoplecounting", name="peoplecounting");
     *
     */
    public function view(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $usr = $this->get('security.context')->getToken()->getUser();
        $operatorId = $usr->getId();
        $active_event = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        $event = $this->getDoctrine()->getRepository('AppBundle\Entity\event')->find($active_event);
        $em->flush();
        $venues = $event->getVenues();
        dump(get_class($venues));
        //$em = $this->getDoctrine()->getManager();
        //$qb = $em->createQueryBuilder();
        //$qb->select('u')
         //   ->from('AppBundle\Entity\venue', 'u');
        //$query = $qb->getQuery();
        //$venues = $query->getArrayResult();
        //
        
       // foreach ($venues as $key => $value) {
         //   $venues[$key]['count'] = $em->getRepository('AppBundle\Entity\venue')->getvenuecount($value['id']);
        //}
        
        return $this->render('peoplecounting.html.twig', array('venues' => $venues));
    }

    /**
     * @Route("/venue/skew/{id}", name="skew");
     *
     */
    public function skew(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $skew = new skew();

        
        $timestamp = $em->getRepository('AppBundle\Entity\venue')->getvenuedoors($id);
        $em->flush();
        $venue = $em->getRepository('AppBundle\Entity\venue')->find($id);
        
      
        $venue = $em->getRepository('AppBundle\Entity\venue')->findOneBy(array('id' => $id));
        
        $skew->setVenueId($venue);
        
        $form = $this->createForm(new SkewType(), $skew);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 4) save the skew!
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($skew);
            $em->flush();
            
            $skew = new skew();
            $skew->setVenueId($venue);
            $form = $this->createForm(new SkewType(), $skew);
            //return $this->redirectToRoute('skew', ['id' => $id]);
        }

        $em->flush();
        $skews = $em->getRepository('AppBundle\Entity\skew')->getvenueskew($id, $timestamp);

        return $this->render('skew.html.twig', array('skews' => $skews, 'venue' => $venue, 'form' => $form->createView()));
    }
    /**
     * @Route("/venue/doors/{id}", name="venue_doors");
     *
     */
    public function doors($id)
    {
        $em = $this->getDoctrine()->getManager();
        $venue = $em->getRepository('AppBundle\Entity\venue')->find($id);
        $venue->setDoors(new \DateTime());
        $name = $venue->getName();
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice','Doors set for '.$name);
        return $this->redirectToRoute('peoplecounting');
    }
    /**
     * @Route("/venue/jsondata", name="venue_json_data");
     *
     */
    public function venue_json_data(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('u')
            ->from('AppBundle\Entity\venue', 'u');
        $query = $qb->getQuery();
        $venues = $query->getArrayResult();

        $output = array();
        foreach ($venues as $key => $value) {
            $venues[$key]['count'] = $em->getRepository('AppBundle\Entity\venue')->getvenuecount($value['id']);
            $status = $em->getRepository('AppBundle\Entity\venue')->getvenuestatus($value['id']);
            if ($status) {   $venues[$key]['status'] = "true"; }else{  $venues[$key]['status'] = "false"; }
            $status = $em->getRepository('AppBundle\Entity\venue')->getpeoplecountingstatus();
            if ($status) {   $venues['people_counting_status'] = "true"; }else{  $venues['people_counting_status'] = "false"; }
        }
        
        if ($venues)
        {
            $response = new JsonResponse();
            $response->setData($venues);

        } else {
            $response->setContent('Hello World');
            $response->headers->set('Content-Type', 'text/plain');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return $response;


        //return $response;
    }

}