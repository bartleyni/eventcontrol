<?php
namespace AppBundle\Entity;
use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;
class VenueCountAlertsRepository extends EntityRepository
{
    public function getVenueEventCountAlerts($id)
    {
        return $this->getEntityManager()->createQuery('SELECT p.id, p.description, p.upDownBoth, p.triggered,IDENTITY(p.venueEvent) as venueEvent, p.count FROM AppBundle\Entity\VenueCountAlerts p  WHERE p.venueEvent = :id')->setParameter('id', $id)->getResult();
    }
}
