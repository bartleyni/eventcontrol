<?php
namespace AppBundle\Entity;
use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;
class venue_eventRepository extends EntityRepository
{
    public function getEventVenueNotInList($eventId, $venue_list)
    {
        return $this->getEntityManager()->createQuery('SELECT venue_event FROM AppBundle\Entity\venue_event venue_event WHERE venue_event.event_id = :id AND venue_event.venue_id NOT IN (:venues)')->setParameter('id', $eventId)->setParameter('venues', $venue_list)->getResult();
    }
}