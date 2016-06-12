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
            new \Twig_SimpleFunction('activeTotalLogs', array($this, 'getTotalLogs')),
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
    
    public function getTotalLogs()
    {
        $em = $this->doctrine->getManager();
        
        //$logs = $em->getRepository('AppBundle\Entity\log_entries');
        
        $qb = $em->createQueryBuilder(); 
        
        $qb
            ->select('count(entry.id)')
            ->from('AppBundle\Entity\log_entries', 'entry')
            ;

        $totalLogs = $qb->getQuery()->getSingleScalarResult();
        //$totalLogs = 25;
        return $totalLogs;
    }
    
    public function getName()
    {
        return 'AppExtension';
    }
}