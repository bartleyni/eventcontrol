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
    public function getLatestUPS()
    {
        //$statuses = $this->getEntityManager()->createQuery('SELECT status1.id, status1.status, status1.timestamp, ups.name, ups.location, ups.power, ups.id FROM AppBundle\Entity\UPS_Status status1 JOIN status1.UPS ups WHERE status1.timestamp=(SELECT MAX(status2.timestamp) FROM AppBundle\Entity\UPS_Status status2 WHERE status1.UPS=status2.UPS)');
        $statuses = $this->getEntityManager()->createQuery('SELECT status1.id, status1.status, status1.timestamp, ups.name, ups.location, ups.power, ups.id FROM AppBundle\Entity\UPS_Status status1 JOIN status1.UPS ups WHERE status1.timestamp=(SELECT MAX(status2.timestamp) FROM AppBundle\Entity\UPS_Status status2 WHERE status1.UPS=status2.UPS)')->getResult();
        
        //$now = new DateTime();
        
        return $statuses;
    }
}
