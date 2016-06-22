<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Description of ControlRoomLEDRepository
 *
 * @author Nick
 */

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;

class ControlRoomLEDRepository extends EntityRepository
{
    public function getLatestLED()
    {
        $statuses = $this->getEntityManager()->createQuery('SELECT status1.id, status1.ledRef, status1.timestamp, status1.colour, status1.brightness FROM AppBundle\Entity\ControlRoomLED status1 WHERE status1.timestamp=(SELECT MAX(status2.timestamp) FROM AppBundle\Entity\ControlRoomLED status2 WHERE status1.ledRef=status2.ledRef)')->getResult();   
        return $statuses;
    }
}
