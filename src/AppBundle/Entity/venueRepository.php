<?php

namespace AppBundle\Entity;

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;


class venueRepository extends EntityRepository
{
    public function getvenuecount($id)
    {
        $em = $this->getDoctrine()->getManager();
        $cameras = $em->getRepository('AppBundle\Entity\venue_camera')->getvenuecameras($id);

        //$output['running_count_in']=$current_data[running_count_in]-$doors_data[running_count_in];
        //$output['running_count_out']=$current_data[running_count_out]-$doors_data[running_count_out];

        return $cameras;

    }
}