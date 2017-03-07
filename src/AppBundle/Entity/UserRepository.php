<?php

namespace AppBundle\Entity;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\NoResultException;

class UserRepository extends EntityRepository implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        $q = $this
            ->createQueryBuilder('u')
            ->select('u, g')
            ->leftJoin('u.groups', 'g')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
        ;

        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no record matching the criteria.
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            throw new UsernameNotFoundException(sprintf('Unable to find an active User object identified by "%s".', $username), null, 0, $e);
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }
    
    public function getActiveEvents($userId)
    {
        $now = new \DateTime();
        
        $user_events = $this->getEntityManager()->createQuery('SELECT event.name, event.id FROM AppBundle\Entity\event event JOIN event.Users Users WHERE Users.id = :id AND :nowdate BETWEEN event.event_log_start_date AND event.event_log_stop_date')->setParameter('id', $userId)->setParameter('nowdate', $now)->getResult();

        return $user_events;
    }
    
    public function getActiveEventEndTime($userId)
    {
        $now = new \DateTime();

        //$user_event = $this->getEntityManager()->createQuery('SELECT IDENTITY(user_event.event_id) FROM AppBundle\Entity\user_events user_event JOIN user_event.event_id event WHERE user_event.User_id = :id AND :nowdate BETWEEN event.event_log_start_date AND event.event_log_stop_date')->setParameter('id', $userId)->setParameter('nowdate', $now)->getOneOrNullResult();
        $selected_event = $this->getEntityManager()->getRepository('AppBundle\Entity\User')->findOneBy(array('id' => $userId))->getSelectedEvent();
        if($selected_event){
            $event_log_stop_date = $this->getEntityManager()->createQuery('SELECT event.event_log_stop_date FROM AppBundle\Entity\event event WHERE event.id = :id')->setParameter('id', $selected_event)->getOneOrNullResult();
            
            return $event_log_stop_date['event_log_stop_date'];
        }
        else {
            return Null;
        }
    }
}
