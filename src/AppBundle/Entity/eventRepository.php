<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Description of eventRepository
 *
 * @author Nick
 */

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;

class eventRepository extends EntityRepository
{
    public function getActiveEvents()
    {
        
        $now = new \DateTime();
        
        $events = $this->getEntityManager()->createQuery('SELECT event.id, event.name, event.event_log_stop_date FROM AppBundle\Entity\event event WHERE :nowdate BETWEEN event.event_log_start_date AND event.event_log_stop_date')->setParameter('nowdate', $now)->getResult();
        
        return $events;
    }
}
