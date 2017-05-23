t<?php

namespace AppBundle\Entity;

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;


class skewRepository extends EntityRepository
{

    public function getvenueskew($id, $timestamp)
    {
        return $this->getEntityManager()->createQuery('SELECT p.id, p.skew_in, p.skew_out, IDENTITY(p.venue_id) as venue_id, p.timestamp FROM AppBundle\Entity\skew p  WHERE p.venue_id = :id AND p.timestamp > :timestamp ORDER BY p.timestamp DESC ')->setParameter('timestamp', $timestamp)->setParameter('id', $id)->getResult();
    }

}