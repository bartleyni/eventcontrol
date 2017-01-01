<?php
namespace AppBundle\Entity;
use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;
class venue_cameraRepository extends EntityRepository
{
    public function getvenuecameras($id)
    {
       //('SELECT IDENTITY(user_event.event_id) FROM AppBundle\Entity\user_events user_event JOIN user_event.event_id event WHERE user_event.User_id = :id AND :nowdate BETWEEN event.event_log_start_date AND event.event_log_stop_date')->setParameter('id', $userId)->setParameter('nowdate', $now)->getOneOrNullResult();

        return $this->getEntityManager()->createQuery('SELECT IDENTITY(p.camera_id) as camera_id, p.inverse, camera.name FROM AppBundle\Entity\venue_camera p JOIN p.camera_id camera  WHERE p.venue_id = :id')->setParameter('id', $id)->getResult();
    }
    public function getcameravenue($id)
    {
        return $this->getEntityManager()->createQuery('SELECT IDENTITY(p.venue_id) as venue_id FROM AppBundle\Entity\venue_camera p  WHERE p.camera_id = :id')->setParameter('id', $id)->getResult();
    }
}