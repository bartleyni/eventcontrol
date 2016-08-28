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
        if ($location){
            return $this->getEntityManager()->createQuery('SELECT DISTINCT log_entry.location FROM AppBundle\Entity\log_entries log_entry WHERE log_entry.event = :event AND log_entry.location LIKE :location')->setParameter('event', $event)->setParameter('location', '%'.$location.'%')->getResult();
        } else {
            return $this->getEntityManager()->createQuery('SELECT DISTINCT log_entry.location FROM AppBundle\Entity\log_entries log_entry WHERE log_entry.event = :event')->setParameter('event', $event)->getResult();
        }
    }
    
    public function getReportedByLookup($event, $reported=null)
    {
        if ($reported){
            return $this->getEntityManager()->createQuery('SELECT DISTINCT log_entry.reported_by FROM AppBundle\Entity\log_entries log_entry WHERE log_entry.event = :event AND log_entry.reported_by LIKE :reported')->setParameter('event', $event)->setParameter('reported', '%'.$reported.'%')->getResult();
        } else {
            return $this->getEntityManager()->createQuery('SELECT DISTINCT log_entry.reported_by FROM AppBundle\Entity\log_entries log_entry WHERE log_entry.event = :event')->setParameter('event', $event)->getResult();
        }
        
    }
}
