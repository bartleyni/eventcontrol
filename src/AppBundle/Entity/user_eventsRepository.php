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
        
        $user_event = $this->getEntityManager()->createQuery('SELECT IDENTITY(user_event.event_id) FROM AppBundle\Entity\user_events user_event JOIN user_event.event_id event WHERE user_event.User_id = :id AND :nowdate BETWEEN event.event_log_start_date AND event.event_log_stop_date')->setParameter('id', $userId)->setParameter('nowdate', $now)->getOneOrNullResult();
        
        return $this->getEntityManager()->getRepository('AppBundle\Entity\event')->findOneBy(array('id' => $user_event));
        
        //return $user_event;
        
        //$return $this->getEntityManager()->createQuery('SELECT event FROM AppBundle\Entity\event event
    }
    public function getActiveEventEndTime($userId)
    {
        $now = new \DateTime();

        $user_event = $this->getEntityManager()->createQuery('SELECT IDENTITY(user_event.event_id) FROM AppBundle\Entity\user_events user_event JOIN user_event.event_id event WHERE user_event.User_id = :id AND :nowdate BETWEEN event.event_log_start_date AND event.event_log_stop_date')->setParameter('id', $userId)->setParameter('nowdate', $now)->getOneOrNullResult();

        return $this->getEntityManager()->createQuery('SELECT event.event_log_stop_date FROM AppBundle\Entity\event event WHERE event.id = :id')->setParameter('id', $user_event)->getOneOrNullResult();


        //return $user_event;

        //$return $this->getEntityManager()->createQuery('SELECT event FROM AppBundle\Entity\event event
    }
}
