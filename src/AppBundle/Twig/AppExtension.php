<?php
//src/AppBundle/Twig/AppExtension.php

namespace AppBundle\Twig;

use Doctrine\ORM\EntityManager;

class AppExtension extends \Twig_Extension
{

    protected $em;
    
    //public function __construct(EntityManager $em)
    //{
    //    $this->em = $em;
    //}
    
    public function getFunctions()
    {
        return array(
            'eventName' => new \Twig_SimpleFunction($this, 'getEventName'),
            'eventTest' => 'Hello Test World'
        );
    }
    
    public function getEventName()
    {
        $em = $this->getDoctrine()->getManager();
        
        $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(
            array('event_active' => true));

        $em->flush();
        $eventName = $event->getName();
        
        return $eventName;
    }
    
    public function getName()
    {
        return 'AppExtension';
    }
}