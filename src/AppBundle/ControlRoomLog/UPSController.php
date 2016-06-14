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
use Doctrine\ORM\Query\ResultSetMapping;

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
        
        $upsi = $em->getRepository('AppBundle\Entity\UPS');
        
        $qb = $em->createQueryBuilder();
        
        $qb
            ->select('ups.id, status.timestamp, status.status, ups.name, ups.location, ups.power')
            ->from('AppBundle\Entity\UPS_Status', 'status')
            ->leftJoin('AppBundle\Entity\UPS', 'ups', 'WITH', 'ups.id = status.UPS')
            //->orderBy('status.timestamp', 'DESC')
            //->leftJoin('AppBundle\Entity\UPS_Status', 'status2', 'WITH', 'status.id = status2.id')
            //->where('status.timestamp < status2.timestamp')
            //->andWhere('status2.timestamp is NULL')
            //->orderBy('ups.id', 'ASC')
            ->groupBy('ups.id')
            //->addOrderBy('status2.timestamp', 'DESC')
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
