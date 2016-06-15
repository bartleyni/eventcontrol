<?php

namespace AppBundle\ControlRoomLog;
use AppBundle\Entity\venue;
use AppBundle\Entity\camera;
use AppBundle\Entity\venue_camera;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;

class VenueController extends Controller
{
    /**
     * @Route("/venue/jsondata", name="Venue json data");
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