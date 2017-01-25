<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Alert;
use Doctrine\ORM\Event\LifecycleEventArgs;
use DZunke\SlackBundle;

class AlertListener
{
    private $slackBundle_client;
    private $slackBundle_identity_bag;
    //private $slackBundle_connection;

    public function __construct($client, $identity_bag)
    {
        $this->slackBundle_client = $client;
        $this->slackBundle_identity_bag = $identity_bag;
        //$this->slackBundle_attachment = $attachment;
        //$this->slackBundle_connection = $connection;
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        //die('Something is being inserted!');
        
        $entity = $args->getEntity();
        if ($entity instanceof Alert) {
            $this->postToSlack($entity);
        }
    }
    
    private function postToSlack(Alert $alert)
    {
        $unformatted_message = $alert->getMessage();
        $breaks = array("<br />","<br>","<br/>");  
        $message = str_ireplace($breaks, "\r\n", $unformatted_message);  
        $client   = $this->slackBundle_client;
        
        $attachment = new DZunke\SlackBundle\Slack\Entity\MessageAttachment();
        $attachment->setColor('danger');
        $attachment->addField('text', $message');
        
        $slackrResponse = $client->send(
            \DZunke\SlackBundle\Slack\Client\Actions::ACTION_POST_MESSAGE,
            [
                //'identity' => $this->get('dz.slack.identity_bag')->get('echo_charlie'),
                'identity' => $this->slackBundle_identity_bag->get('echo_charlie'),
                'channel'  => '#alerts',
                'text'     => "Alert!",
                "attachment" => $attachment
            ]
        );
    }
}
