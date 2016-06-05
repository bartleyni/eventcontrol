<?php
//src/AppBundle/Twig/AppExtension.php

namespace AppBundle\Twig;

use Doctrine\ORM\EntityManager;

class AppExtension extends \Twig_Extension
{

    protected $em;
    
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function getGlobals()
    {
        return array ();
    }
    
    public function getName()
    {
        return 'AppExtension';
    }
}