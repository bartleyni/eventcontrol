<?php

namespace AppBundle\Entity;

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;


class venue_cameraRepository extends EntityRepository
{

public function getvenuecameras($id)
{
return $this->getEntityManager()->createQuery('SELECT p FROM AppBundle\Entity\venue_camera p  WHERE p.venue_id ')->setParameter('id', $id)->getResult();
}

public function getcameravenue($id)
{
    return $this->getEntityManager()->createQuery('SELECT IDENTITY(p.venue_id) FROM AppBundle\Entity\venue_camera p  WHERE p.camera_id = :id')->setParameter('id', $id)->setMaxResults(1)->getOneOrNullResult();
}
}