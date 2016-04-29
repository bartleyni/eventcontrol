<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    /**
     * @Route("/media/{filename}/{type}", name="media")
     */
    public function fileDisplayAction(Request $request, $filename=null, $type=null)
    {
        $response = new Response();
        $filetype = null;
        
        if ($type == "pdf"){
            $filetype = ".pdf";
        }
                
        if ($filename && $filetype){
            $file = '../media/'.$filename.$filetype;
            $response = new BinaryFileResponse($file);
            return $response;
            
        }else{
                return $this->redirectToRoute('full_log');
        }
    }
}