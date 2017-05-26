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

    public function __construct($client, $identity_bag)
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
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle\Entity\User')->findAll();
        $fcmClient = $this->getContainer()->get('redjan_ym_fcm.client');
        $notification = $fcmClient->createDeviceNotification(
            $alert->getTitle(), 
            $alert->getMessage(),
            'eKirY29t09I:APA91bGDUn-rq0Iai6NEmC7Pmi1sE_cvdglGU1aPW4NxqRRZ8U-F_rP4ZAN_vkc-tctRpzPjgy8UqUKrDPiPX6x2p7YoFz4NgO3QsukOEvWjJDcyx6bS43RUq1i986N6rtD-2tlt7fD6'
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
