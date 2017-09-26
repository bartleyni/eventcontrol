<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Description of UPS_StatusRepository
 *
 * @author Nick
 */

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;

class UPS_StatusRepository extends EntityRepository
{
    public function getLatestUPS($event=Null)
    {
        if($event){
            $statuses = $this->getEntityManager()->createQuery('SELECT status1.id, status1.status, status1.timestamp, status1.lineVoltage, status1.loadPercentage, status1.batteryVoltage, status1.timeLeft, ups.name, ups.location, ups.power, ups.id, event.id as eId FROM AppBundle\Entity\UPS_Status status1 JOIN status1.UPS ups JOIN ups.events event WHERE status1.timestamp=(SELECT MAX(status2.timestamp) FROM AppBundle\Entity\UPS_Status status2 WHERE status1.UPS=status2.UPS) AND event.id = :event')->setParameter('event', $event) ->getResult();
            
        } else {
            $statuses = $this->getEntityManager()->createQuery('SELECT status1.id, status1.status, status1.timestamp, status1.lineVoltage, status1.loadPercentage, status1.batteryVoltage, status1.timeLeft, ups.name, ups.location, ups.power, ups.id FROM AppBundle\Entity\UPS_Status status1 JOIN status1.UPS ups WHERE status1.timestamp=(SELECT MAX(status2.timestamp) FROM AppBundle\Entity\UPS_Status status2 WHERE status1.UPS=status2.UPS)') ->getResult();
        }
        $now = new \DateTime();
        
        foreach ($statuses as $key => $status)
        {
            $interval1 = date_diff($status['timestamp'], $now, TRUE);
            
            $minutes = $interval1->days * 24 * 60;
            $minutes += $interval1->h * 60;
            $minutes += $interval1->i;
            
            if ($minutes > 9)
            {
                $statuses[$key]['status'] = 'Timeout';
                $statuses[$key]['timeout'] = $minutes;
            }
        }
        
        return $statuses;
    }
    
    public function getLatestSpecificUPS($ups)
    {
        #$status = $this->getEntityManager()->createQuery('SELECT status1.id, status1.status, status1.timestamp, status1.lineVoltage, status1.loadPercentage, status1.batteryVoltage, status1.timeLeft, ups.name, ups.location, ups.power, ups.id, event.id as eId FROM AppBundle\Entity\UPS_Status status1 JOIN status1.UPS ups JOIN ups.events event WHERE status1.timestamp=(SELECT MAX(status2.timestamp) FROM AppBundle\Entity\UPS_Status status2 WHERE status1.UPS=status2.UPS) AND ups.id = :ups')->setParameter('ups', $ups)->setMaxResults(1)->getOneOrNullResult();
        #Modified to reduce server load
        $status = $this->getEntityManager()->createQuery('SELECT status1.id, status1.status, status1.timestamp, status1.lineVoltage, status1.loadPercentage, status1.batteryVoltage, status1.timeLeft, ups.name, ups.location, ups.power, ups.id, event.id as eId FROM AppBundle\Entity\UPS_Status status1 JOIN status1.UPS ups JOIN ups.events event WHERE ups.id = :ups')->setParameter('ups', $ups)->setMaxResults(1)->getOneOrNullResult();
        
        return $status;
    }
}
