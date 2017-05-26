<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Alert;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManager;
use DZunke\SlackBundle;
use \DZunke\SlackBundle\Slack\Entity\MessageAttachment;

class AlertListener
{
    private $slackBundle_client;
    private $slackBundle_identity_bag;
    //private $slackBundle_connection;
    protected $em;

    public function __construct($client, $identity_bag, EntityManager $em)
    {
        $this->slackBundle_client = $client;
        $this->slackBundle_identity_bag = $identity_bag;
        //$this->slackBundle_connection = $connection;
        $this->em = $em;
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {        
        $entity = $args->getEntity();
        if ($entity instanceof Alert) {
            $this->postToSlack($entity);
            $this->sendFirebaseMessage($entity);
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
    
    private function sendFirebaseMessage(Alert $alert)
    {
        $users = $this->em->getRepository('AppBundle\Entity\User')->findAll();
        foreach($users as $user){
            $token = $user->getFirebaseID();
            if($token){
                $fcmClient = $this->getContainer()->get('redjan_ym_fcm.client');
                $notification = $fcmClient->createDeviceNotification(
                    $alert->getTitle(), 
                    $alert->getMessage(),
                    $token
                );
                if($alert->getFoR())
                {
                    $notification->setData(["type" => $alert->getFoR(),]);
                } else {
                    $notification->setData(["type" => "",]);
                }
                $fcmClient->sendNotification($notification);
            }
        }
    }
    
    
}
