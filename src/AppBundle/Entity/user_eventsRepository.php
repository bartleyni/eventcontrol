<?php

namespace AppBundle\Entity;

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;

class user_eventsRepository extends EntityRepository
{
    public function getEventUsers($eventId)
    {
        return $this->getEntityManager()->createQuery('SELECT IDENTITY(user_event.User_id) FROM AppBundle\Entity\user_events user_event WHERE user_event.event_id = :id')->setParameter('id', $eventId)->getResult();
    }
    
    public function getEventUsersNotInList($userIdList)
    {
        return $this->getEntityManager()->createQuery('SELECT IDENTITY(user_event.User_id) FROM AppBundle\Entity\user_events user_event WHERE user_event.event_id = :id AND user_event.User_id NOT IN (:users)')->setParameter('id', $eventId)->setParameter('users', $userIdList)->getResult();
    }
}