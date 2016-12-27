<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Description of locationsRepository
 *
 * @author Nick
 */

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;

class locationsRepository extends EntityRepository
{
    public function getEventLocationLookup($event, $location=null)
    {
        if ($location){
            return $this->getEntityManager()->createQuery('SELECT DISTINCT location.locationText as location FROM AppBundle\Entity\Locations location WHERE location.event = :event AND location.locationText LIKE :location')->setParameter('event', $event)->setParameter('location', '%'.$location.'%')->getResult();
        } else {
            return $this->getEntityManager()->createQuery('SELECT DISTINCT location.locationText as location FROM AppBundle\Entity\Locations location WHERE location.event = :event')->setParameter('event', $event)->getResult();
        }
    }
}
