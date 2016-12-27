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
            return $this->getEntityManager()->createQuery('SELECT DISTINCT locations.locationText as location FROM AppBundle\Entity\Locations locations WHERE locations.event = :event AND locations.locationText LIKE :locationIn')->setParameter('event', $event)->setParameter('locationIn', '%'.$location.'%')->getResult();
        } else {
            return $this->getEntityManager()->createQuery('SELECT DISTINCT locations.locationText as location FROM AppBundle\Entity\Locations locations WHERE locations.event = :event')->setParameter('event', $event)->getResult();
        }
    }
}
