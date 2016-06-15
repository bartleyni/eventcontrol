<?php

namespace AppBundle\Entity;

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;


class venueRepository extends EntityRepository
{
    public function getvenuecount($id)
    {
       
        $cameras = $this->getEntityManager()->getRepository('AppBundle\Entity\venue_camera')->getvenuecameras($id);
        $output = array();
        foreach ($cameras as $camera) {
            if($camera['inverse']){
                $camera_count = $this->getEntityManager()->getRepository('AppBundle\Entity\camera')->getcameracount($camera['camera_id']);
                print_r($camera['camera_id']);
                print_r($camera_count);
                $output['running_count_in'] += $camera_count['running_count_out'];
                $output['running_count_out'] += $camera_count['running_count_in'];
            }else {
                $camera_count = $this->getEntityManager()->getRepository('AppBundle\Entity\camera')->getcameracount($camera['camera_id']);
                print_r($camera['camera_id']);
                print_r($camera_count);
                $output['running_count_in'] += $camera_count['running_count_in'];
                $output['running_count_out'] += $camera_count['running_count_out'];
            }
        }
        //$output['running_count_in']=$current_data[running_count_in]-$doors_data[running_count_in];
        //$output['running_count_out']=$current_data[running_count_out]-$doors_data[running_count_out];

        return $output;

    }
}