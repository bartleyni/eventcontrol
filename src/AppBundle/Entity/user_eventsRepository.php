<?php

namespace AppBundle\Entity;

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;

class user_eventsRepository extends EntityRepository
{
    public function getEventUsers($eventId)
    {
        return $this->getEntityManager()->createQuery('SELECT user_event FROM AppBundle\Entity\user_events user_event WHERE user_event.event_id = :id')->setParameter('id', $eventId)->getResult();
    }
    
    public function getEventUsersNotInList($eventId, $userIdList)
    {
        return $this->getEntityManager()->createQuery('SELECT user_event FROM AppBundle\Entity\user_events user_event WHERE user_event.event_id = :id AND user_event.User_id NOT IN (:users)')->setParameter('id', $eventId)->setParameter('users', $userIdList)->getResult();
    }
    
    public function getActiveEvent($userId)
    {
        $now = new \DateTime();
        
        //$active_event = $this->getEntityManager()->createQuery('SELECT event.id FROM AppBundle\Entity\event event WHERE event.date1 = :date2 AND event.date2 = :date1')->setParameter('date1', $date)->setParameter('date2', $date)->getResult();
    
        
        return $this->getEntityManager()->createQuery('SELECT user_event.event_id FROM AppBundle\Entity\user_events user_event WHERE user_event.User_id = :id AND :nowdate BETWEEN user_event.event_id.event_log_start_date AND user_event.event_id.event_log_stop_date')->setParameter('id', $userId)->setParameter('nowdate', $now)->getResult();
    }
}
