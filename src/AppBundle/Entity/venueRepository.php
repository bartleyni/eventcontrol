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
        $timestamp = $this->getEntityManager()->getRepository('AppBundle\Entity\venue')->getvenuedoors($id);
        $skews = $this->getEntityManager()->getRepository('AppBundle\Entity\skew')->getvenueskew($id, $timestamp);

        foreach ($cameras as $camera) {
            if ($camera['inverse']) {
                $camera_doors = $this->getEntityManager()->getRepository('AppBundle\Entity\camera')->getcameradoors($camera['camera_id'], $timestamp);
                 $camera_count = $this->getEntityManager()->getRepository('AppBundle\Entity\camera')->getcameracount($camera['camera_id']);
                print_r($this->getEntityManager()->getRepository('AppBundle\Entity\camera')->iscamerauptodate($camera['camera_id']));
                $output['running_count_in'] += $camera_count['running_count_out'] - $camera_doors['running_count_out'];
                $output['running_count_out'] += $camera_count['running_count_in'] - $camera_doors['running_count_in'];
            } else {

                $camera_doors = $this->getEntityManager()->getRepository('AppBundle\Entity\camera')->getcameradoors($camera['camera_id'], $timestamp);
                $camera_count = $this->getEntityManager()->getRepository('AppBundle\Entity\camera')->getcameracount($camera['camera_id']);
                //echo "print camre doors";
                //print_r($camera_doors);
                //echo "print camera count";
                //print_r($camera_count);
                $output['running_count_in'] += $camera_count['running_count_in'] - $camera_doors['running_count_in'];
                $output['running_count_out'] += $camera_count['running_count_out'] - $camera_doors['running_count_out'];
            }
            //echo "print before output";
            //print_r($output);
            foreach ($skews as $skew) {
                $output['running_count_in'] += $skew['skew_in'];
                $output['running_count_out'] += $skew['skew_out'];
            }
            //echo "print after output";
            //print_r($output);
            //$output['running_count_in']=$current_data[running_count_in]-$doors_data[running_count_in];
            //$output['running_count_out']=$current_data[running_count_out]-$doors_data[running_count_out];
        }
        return $output;
    }
}
    