<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Description of GroupRepository
 *
 * @author Nick
 */

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;

class GroupRepository extends EntityRepository
{
    public function getEventUsers($event)
    {
        $em = $this->getEntityManager();
        
        $users = $em->getRepository('AppBundle\Entity\User')->findBy(array('event' => $event, 'group' => $this));
        
        return $users;
    }
}
