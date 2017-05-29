<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Alert;
use AppBundle\Entity\Queue;
use AppBundle\Entity\User;
use AppBundle\Entity\event;
use Doctrine\ORM\Event\LifecycleEventArgs;
use RedjanYm\FCMBundle;
use DZunke\SlackBundle;
use \DZunke\SlackBundle\Slack\Entity\MessageAttachment;
use Doctrine\ORM\EntityManager;

class AlertListener
{
    private $slackBundle_client;
    private $slackBundle_identity_bag;
    //private $slackBundle_connection;
    //protected $em;
    protected $container;

    public function __construct($client, $identity_bag,\Symfony\Component\DependencyInjection\Container $container)
    {
        $this->slackBundle_client = $client;
        $this->slackBundle_identity_bag = $identity_bag;
        //$this->slackBundle_connection = $connection;
        $this->container = $container;
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {        
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        $users = $em->getRepository('AppBundle\Entity\User')->findAll();
                
        if ($entity instanceof Alert) {
            $this->postToSlack($entity);
            if ($users) {
                $this->sendFirebaseMessage($entity, $users);
            }
        }
    }
    
    public function postPersist(LifecycleEventArgs $args)
    {        
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        
                
        if ($entity instanceof Alert) {
            $alert_queue = new Queue();
            $alert_queue->setAlert($entity);                  
            $em->persist($alert_queue);
            $em->flush();
        }
    }
    
    private function postToSlack(Alert $alert)
    {
        $breaks = array("<br />","<br>","<br/>");
        $unformatted_title = $alert->getTitle();
        $title = str_ireplace($breaks, "\r\n", $unformatted_title);
        $unformatted_message = $alert->getMessage();
        $message = str_ireplace($breaks, "\r\n", $unformatted_message); 
        
        $client   = $this->slackBundle_client;
        
        $attachment = new \DZunke\SlackBundle\Slack\Entity\MessageAttachment();
        $attachment->setColor('warning');
        $attachment->setText($message);
        $attachment->setFallback($message);
        
        $slackrResponse = $client->send(
            \DZunke\SlackBundle\Slack\Client\Actions::ACTION_POST_MESSAGE,
            [
                'identity' => $this->slackBundle_identity_bag->get('echo_charlie'),
                'channel'  => '#alerts',
                'text'     => $title,
                'attachments' => [$attachment],
            ]
        );
    }
    
    private function sendFirebaseMessage(Alert $alert, $users)
    {
        foreach($users as $user){
            $token = $user->getFirebaseID();
            if($token){
                //$fcmClient = $this->getContainer()->get('redjan_ym_fcm.client');
                $fcmClient = $this->container->get('redjan_ym_fcm.client');
                $notification = $fcmClient->createDeviceNotification(
                    "", 
                    "",
                    $token
                );
                if($alert->getFoR())
                {
                    $notification->setData(["type" => $alert->getFoR(), "title" => $alert->getTitle(), "msg" => $alert->getMessage(),]);
                } else {
                    $notification->setData(["type" => "", "title" => $alert->getTitle(), "msg" => $alert->getMessage(),]);
                }
                $fcmClient->sendNotification($notification);
            }
        }
    }
    
    
}
