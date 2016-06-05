<?php
//src/AppBundle/Twig/AppExtension.php

namespace AppBundle\Twig;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\EntityManager;

class AppExtension extends \Twig_Extension
{
    private $em;
  
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->entityManager = $em;
    }
    
    public function getGlobals()
    {
        return array("GlobalTest" => "Hello Test",);
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('activeEventName', array($this, 'getEventName')),
        );
    }
    
    public function getEventName()
    {
        $event = $em->doctrine()->getManager()->getRepository('AppBundle\Entity\event')->findOneBy(array('event_active' => true));

        $eventName = $event->getName();
        
        //$eventName = 'test';
        
        return $eventName;
    }
    
    public function getName()
    {
        return 'AppExtension';
    }
}