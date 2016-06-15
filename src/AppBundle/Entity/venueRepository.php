<?php

namespace AppBundle\Entity;

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;


class venueRepository extends EntityRepository
{
    public function getvenuedoors($id)
    {
        //return "hi";
        $doors = $this->getEntityManager()->createQuery('SELECT p.doors FROM AppBundle\Entity\venue p  WHERE p.id = :id')->setParameter('id', $id)->setMaxResults(1)->getOneOrNullResult();
        return $doors['doors'];
    }
    public function getvenuecount($id)
    {
       
        $cameras = $this->getEntityManager()->getRepository('AppBundle\Entity\venue_camera')->getvenuecameras($id);
        $output = array();
        foreach ($cameras as $camera) {
            if($camera['inverse']){
                $camera_count = $this->getEntityManager()->getRepository('AppBundle\Entity\camera')->getcameracount($camera['camera_id']);
                $output['running_count_in'] += $camera_count['running_count_out'];
                $output['running_count_out'] += $camera_count['running_count_in'];
            }else
                $timestamp = $em->getRepository('AppBundle\Entity\venue')->getvenuedoors($id);
                print_r($timestamp);
                print_r($this->getEntityManager()->getRepository('AppBundle\Entity\camera')->getcameradoors($camera['camera_id'], $timestamp));
                $camera_count = $this->getEntityManager()->getRepository('AppBundle\Entity\camera')->getcameracount($camera['camera_id']);
                $output['running_count_in'] += $camera_count['running_count_in'];
                $output['running_count_out'] += $camera_count['running_count_out'];
            }
        }
        //$output['running_count_in']=$current_data[running_count_in]-$doors_data[running_count_in];
        //$output['running_count_out']=$current_data[running_count_out]-$doors_data[running_count_out];

        return $output;

    }
}