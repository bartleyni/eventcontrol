<?php
//src/AppBundle/Twig/AppExtension.php

namespace AppBundle\Twig;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;

class AppExtension extends \Twig_Extension
{
    private $doctrine;
  
    public function __construct(RegistryInterface $doctrine) {
        $this->doctrine = $doctrine;
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
        $em = $this->doctrine->getManager();
        
        $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(array('event_active' => true));

        $eventName = $event->getName();
        
        //$eventName = 'test';
        
        return $eventName;
    }
    
    public function getName()
    {
        return 'AppExtension';
    }
}