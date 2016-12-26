<?php
namespace AppBundle\Entity;
use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;
class venue_cameraRepository extends EntityRepository
{
    public function getvenuecameras($id)
    {
        return $this->getEntityManager()->createQuery('SELECT (p.camera) as camera, p.inverse FROM AppBundle\Entity\venue_camera p  WHERE p.venue = :id')->setParameter('id', $id)->getResult();
    }
    public function getcameravenue($id)
    {
        return $this->getEntityManager()->createQuery('SELECT IDENTITY(p.venue) as venue FROM AppBundle\Entity\venue_camera p  WHERE p.camera = :id')->setParameter('id', $id)->getResult();
    }
}