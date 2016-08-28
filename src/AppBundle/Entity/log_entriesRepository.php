<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Description of log_entriesRepository
 *
 * @author Nick
 */

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;

class log_entriesRepository extends EntityRepository
{
    public function getLocationLookup($event, $location=null)
    {
        return $this->getEntityManager()->createQuery('SELECT log_entry.location FROM AppBundle\Entity\log_entries log_entry WHERE log_entry.event = :event AND log_entry.location LIKE :location')->setParameter('event', $event)->setParameter('location', '%'.$location.'%')->getResult();
    }
    
    public function getReportedByLookup($event, $reported=null)
    {
        return $this->getEntityManager()->createQuery('SELECT log_entry.reported_by FROM AppBundle\Entity\log_entries log_entry WHERE log_entry.event = :event AND log_entry.reported_by LIKE :reported')->setParameter('event', $event)->setParameter('reported', '%'.$reported.'%')->getResult();
    }
}
