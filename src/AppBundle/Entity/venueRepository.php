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
    public function getvenuestatus($id)
    {
        $cameras = $this->getEntityManager()->getRepository('AppBundle\Entity\venue_camera')->getvenuecameras($id);
        $output = true;
        foreach ($cameras as $camera) {
            $status =$this->getEntityManager()->getRepository('AppBundle\Entity\camera_count')->iscamerauptodate($camera['camera_id']);
            if(!$status){
                $output = false;
            }
            return $output;
        }
    }
    public function getpeoplecountingstatus()
    {
        $venues = $this->getEntityManager()->createQuery('SELECT p.id FROM AppBundle\Entity\venue p')->getResult();

        $output = true;
        foreach ($venues as $venue) {
            $status=$this->getEntityManager()->getRepository('AppBundle\Entity\venue')->getvenuestatus($venue['id']);
            if(!$status){
                $output = false;
            }
            return $output;
        }
    }

    public function getvenuecount($id)
    {
        $cameras = $this->getEntityManager()->getRepository('AppBundle\Entity\venue_camera')->getvenuecameras($id);
        $output = array();
        $timestamp = $this->getEntityManager()->getRepository('AppBundle\Entity\venue')->getvenuedoors($id);
        $skews = $this->getEntityManager()->getRepository('AppBundle\Entity\skew')->getvenueskew($id, $timestamp);
        $output['running_count_in'] = 0;
        $output['running_count_out'] = 0;
        foreach ($cameras as $camera) {
            if ($camera['inverse']) {
                $camera_doors = $this->getEntityManager()->getRepository('AppBundle\Entity\camera_count')->getcameradoors($camera['camera'], $timestamp);
                $camera_count = $this->getEntityManager()->getRepository('AppBundle\Entity\camera_count')->getcameracount($camera['camera']);
                $output['running_count_in'] += $camera_count['running_count_out'] - $camera_doors['running_count_out'];
                $output['running_count_out'] += $camera_count['running_count_in'] - $camera_doors['running_count_in'];
            } else {
                $camera_doors = $this->getEntityManager()->getRepository('AppBundle\Entity\camera_count')->getcameradoors($camera['camera'], $timestamp);
                $camera_count = $this->getEntityManager()->getRepository('AppBundle\Entity\camera_count')->getcameracount($camera['camera']);
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