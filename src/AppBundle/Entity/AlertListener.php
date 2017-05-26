<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Alert;
use Doctrine\ORM\Event\LifecycleEventArgs;
use DZunke\SlackBundle;
use \DZunke\SlackBundle\Slack\Entity\MessageAttachment;

class AlertListener
{
    private $slackBundle_client;
    private $slackBundle_identity_bag;
    //private $slackBundle_connection;

    public function __construct($client, $identity_bag)
    {
        $this->slackBundle_client = $client;
        $this->slackBundle_identity_bag = $identity_bag;
        //$this->slackBundle_connection = $connection;
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
        $fcmClient = $this->getContainer()->get('redjan_ym_fcm.client');
        $notification = $fcmClient-->createDeviceNotification(
            $alert->getTitle(), 
            $alert->getMessage(),
            'dqBQBlPds2Q:APA91bFU3Xk9b9Lym63LzRujJkqsmlhuA1pIr_LGHDcrRj5rDEOwnOQhoPzZS4SJq6BEov21BFwLB47KkVDoQI77RUkeZ3gWlS0uD1gFZRqqlVaxT2u4jOkf2DYjajy-ipDfO4rjdNMD'
        );
        $notification->setData(["type" => "",]);
        $fcmClient->sendNotification($notification);
        
    }
    
    
}
