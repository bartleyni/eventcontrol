<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\UPS;
use AppBundle\Entity\UPS_Status;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Description of UPSController
 *
 * @author Nick
 */
class UPSController extends Controller
{
    /**
     * @Route("/UPS/status", name="UPS_status");
     * 
     */
    public function UPSAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $qb = $em->createQueryBuilder();
        $qb2 = $em->createQueryBuilder(); 
        
        $qb
            ->select('ups.id, status.timestamp, status.status, ups.name, ups.location, ups.power')
            ->from('AppBundle\Entity\UPS_Status', 'status')
            ->leftJoin('AppBundle\Entity\UPS', 'ups', 'WITH', 'ups.id = status.UPS')
            ->where(
            ->orderBy('ups.id', 'ASC')
            ;
        
        $query = $qb->getQuery();
        $ups_statuses = $query->getResult();
        
        if ($ups_statuses)
            {
                $response = new JsonResponse();
                $response->setData($ups_statuses);

        } else {
            $response->setContent('Hello World');
            $response->headers->set('Content-Type', 'text/plain');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        
        return $response;
    }
}
