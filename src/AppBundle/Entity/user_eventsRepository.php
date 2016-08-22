<?php

namespace AppBundle\ControlRoomLog;

use AppBundle\Entity\event;
use AppBundle\Entity\user_events;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class user_eventsRepository extends EntityRepository
{
    public function getEventUsers($eventId)
    {
        return $this->getEntityManager()->createQuery('SELECT user_event.User_Id FROM AppBundle\Entity\user_events user_event WHERE user_event.event_id = :id')->setParameter('id', $eventId)->getResult();
    }
    
    public function getEventUsersNotInList($userIdList)
    {
        return $this->getEntityManager()->createQuery('SELECT user_event.User_Id FROM AppBundle\Entity\user_events user_event WHERE user_event.event_id = :id AND user_event.User_Id NOT IN (:users)')->setParameter('id', $eventId)->setParameter('users', $userIdList)->getResult();
    }
}
