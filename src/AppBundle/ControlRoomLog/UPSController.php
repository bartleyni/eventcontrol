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
        
        $qb2
            ->select('ups.id')
            ->from('AppBundle\Entity\UPS_Status', 'status')
            ;
            
        $query2 = $qb2->getQuery();
        $upsi = $query2->getResult();
        
        $ups_statuses = [];
        
        foreach($upsi as $key => $value) {
            
            $qb
                ->select('ups.id, status.timestamp, status.status, ups.name, ups.location, ups.power')
                ->from('AppBundle\Entity\UPS_Status', 'status')
                ->leftJoin('AppBundle\Entity\UPS', 'ups', 'WITH', 'ups.id = status.UPS')
                ->orderBy('status.timestamp', 'DESC')
                ->where('ups.id = :UPSid')
                ->setParameter('UPSis', $value)
                //->leftJoin('AppBundle\Entity\UPS_Status', 'status2', 'WITH', 'status.id = status2.id')
                //->where('status.timestamp < status2.timestamp')
                //->andWhere('status2.timestamp is NULL')
                //->orderBy('ups.id', 'ASC')
                //->groupBy('ups.id')
                //->addOrderBy('status2.timestamp', 'DESC')
                ->setMaxResults(1)
                ;
        
            $query = $qb->getQuery();
            $ups_status = $query->getResult();
            array_push($ups_statuses, $ups_status);
        }

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
