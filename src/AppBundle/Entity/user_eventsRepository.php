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
        
        $user = $this->getEntityManager()->getRepository('AppBundle\Entity\User')->findOneBy(array('id' => $userId));
        
        $event = null;
        $event = $user->getSelectedEvent();
        
        if($event)
        {
            $eId = $event->getId();
            $user_event = $this->getEntityManager()->createQuery('SELECT IDENTITY(user_event.event_id) FROM AppBundle\Entity\user_events user_event JOIN user_event.event_id event WHERE user_event.User_id = :id AND user_event.event_id = :eId AND :nowdate BETWEEN event.event_log_start_date AND event.event_log_stop_date')->setParameter('id', $userId)->setParameter('nowdate', $now)->setParameter('eId', $eId)->setMaxResults(1)->getOneOrNullResult();
            if(!$user_event)
            {
                $event = null;
            }
        } else {
            $user_event = $this->getEntityManager()->createQuery('SELECT IDENTITY(user_event.event_id) FROM AppBundle\Entity\user_events user_event JOIN user_event.event_id event WHERE user_event.User_id = :id AND :nowdate BETWEEN event.event_log_start_date AND event.event_log_stop_date')->setParameter('id', $userId)->setParameter('nowdate', $now)->setMaxResults(1)->getOneOrNullResult();
            if(!$user_event)
            {
                $event = null;
            } else {
                $event = $this->getEntityManager()->getRepository('AppBundle\Entity\event')->findOneBy(array('id' => $user_event));
                $user->setSelectedEvent($event);
                $this->getEntityManager()->persist($user);
            }
            
        }
        
        return $event;
    }
    
    public function getActiveEvents($userId)
    {
        $now = new \DateTime();
        
        $user_events = $this->getEntityManager()->createQuery('SELECT event.name, event.id FROM AppBundle\Entity\user_events user_event JOIN user_event.event_id event WHERE user_event.User_id = :id AND :nowdate BETWEEN event.event_log_start_date AND event.event_log_stop_date')->setParameter('id', $userId)->setParameter('nowdate', $now)->getResult();
        
        return $user_events;
    }
    
    public function getActiveEventEndTime($userId)
    {
        $now = new \DateTime();

        $user_event = $this->getEntityManager()->createQuery('SELECT IDENTITY(user_event.event_id) FROM AppBundle\Entity\user_events user_event JOIN user_event.event_id event WHERE user_event.User_id = :id AND :nowdate BETWEEN event.event_log_start_date AND event.event_log_stop_date')->setParameter('id', $userId)->setParameter('nowdate', $now)->getOneOrNullResult();

        $event_log_stop_date = $this->getEntityManager()->createQuery('SELECT event.event_log_stop_date FROM AppBundle\Entity\event event WHERE event.id = :id')->setParameter('id', $user_event)->getOneOrNullResult();
        return $event_log_stop_date['event_log_stop_date'];
    }
}
