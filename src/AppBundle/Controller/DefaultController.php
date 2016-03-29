<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
    * @Route("/{hello}/{name}");
    */
    public function helloAction($name, $hello)
    {
        // replace this example code with whatever you need
        return $this->render('hello.html.twig', array('name' => $name, 'hello' => $hello));
    }
}
