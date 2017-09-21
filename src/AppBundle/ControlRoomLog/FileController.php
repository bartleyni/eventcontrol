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
     * @Route("/log_support/{filename}", name="log_support")
     */
    public function logSupport(Request $request, $filename=null)
    {
        $response = new Response();
        
        //Get the link to go back to the log this file came from...
        //$back = Null;
        $em = $this->getDoctrine()->getManager();
        $logFile = $em->getRepository('AppBundle\Entity\logFile')->findOneBy(array('fileName' => $filename));
        $logId = $logFile->getLogEntry()->getId();
        $link = "/entry/".$logId;
        $back = $link;
        
        if ($filename){
            //$file = $this->getParameter('log_support_directory').'/'.$filename;
            
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $allowed_images =  array('gif','png' ,'jpg', 'jpeg', 'bmp', 'GIF','PNG' ,'JPG', 'JPEG', 'BMP');
            if(in_array($ext,$allowed_images) ) {
                /** @var CacheManager */
                $imagineCacheManager = $this->get('liip_imagine.cache.manager');

                $resizedFile = $imagineCacheManager->getBrowserPath($filename, 'log_file_scale');
                $iframe = '<iframe src="'.$resizedFile.'" frameborder=0 scrolling=yes height="900px" class="col-md-12 embed-responsive-item" ></iframe>';
            } else {
                $iframe = '<iframe src="https://eventcontrol.nb221.com/log_support_direct/'.$filename.'"  frameborder=0 scrolling=yes height="900px" class="col-md-12 embed-responsive-item" ></iframe>';
            }
            return $this->render('iframe.html.twig', array('iframe' => $iframe, 'back' => $back));
            //$response = new BinaryFileResponse($file);
            //return $response;

        }else{
                return $this->redirectToRoute('full_log');
        }
    }
    
    /**
     * @Route("/log_support_direct/{filename}", name="log_support_direct")
     */
    public function logSupportDirect(Request $request, $filename=null)
    {
        $response = new Response();

        if ($filename){
            $file = $this->getParameter('log_support_directory').'/'.$filename;
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
