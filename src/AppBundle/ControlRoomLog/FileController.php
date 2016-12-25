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
     * @Route("/media/{type}/{filename}", name="media")
     */
    public function fileDisplayAction(Request $request, $filename=null, $type=null)
    {
        $response = new Response();
        $filetype = null;
        
        if ($type == "pdf"){
            $filetype = ".pdf";
        }
        
        if ($type == "png"){
            $filetype = ".png";
        }
                
        if ($filename && $filetype){
            $file = '../media/'.$filename.$filetype;
            $response = new BinaryFileResponse($file);
            return $response;
            
        }else{
                return $this->redirectToRoute('full_log');
        }
    }
    
    /**
     * @Route("/overlay/{filename}", name="overlay")
     */
    public function eventOverlayAction(Request $request, $filename=null)
    {
        $response = new Response();

        if ($filename){
            $file = $this->getParameter('overlay_directory').'/'.$filename;
            $response = new BinaryFileResponse($file);
            return $response;
            
        }else{
                return $this->redirectToRoute('full_log');
        }
    }
    
    /**
    * @Route("/media/iframe/{type}/{filename}", name="media_iframe");
    * 
    */
    
    public function fileIframeAction($filename=null, $type=null)
    {  
        if($filename)
        {
            $iframe = '<iframe src="https://eventcontrol.nb221.com/media/'.$type.'/'.$filename.'" frameborder=0 scrolling=no height="900px" class="col-md-12 embed-responsive-item" ></iframe>';
            
            return $this->render('iframe.html.twig', array('iframe' => $iframe));
        }
    
    }
    
}