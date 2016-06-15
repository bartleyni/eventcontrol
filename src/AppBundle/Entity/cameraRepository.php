<?php

namespace AppBundle\Entity;

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;


class cameraRepository extends EntityRepository
{

    public function getcameradoors($id, $timestamp)
    {
        //return "hi";
        return $this->getEntityManager()->createQuery('SELECT p.id, p.timestamp, p.count_in, p.running_count_in, p.count_out, p.running_count_out, p.camera_id FROM AppBundle\Entity\camera p  WHERE p.camera_id = :id AND p.timestamp >= :timestamp ORDER BY p.timestamp DESC ')->setParameter('id', $id)->setParameter('timestamp', $timestamp)->setMaxResults(1)->getOneOrNullResult();
    }

    public function getcameracount($id)
    {
        $current_data = $this->getEntityManager()->createQuery('SELECT p.id, p.timestamp, p.count_in, p.running_count_in, p.count_out, p.running_count_out, p.camera_id FROM AppBundle\Entity\camera p  WHERE p.camera_id = :id ORDER BY p.timestamp DESC')->setParameter('id', $id)->setMaxResults(1)->getOneOrNullResult();

        $output['running_count_in']=$current_data[running_count_in];
        $output['running_count_out']=$current_data[running_count_out];
      
        return $output;

    }
    
    
}