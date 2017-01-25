<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Alert;
use Doctrine\ORM\Event\LifecycleEventArgs;
use DZunke\SlackBundle;

class AlertListener
{
    private $slackBundleClient;

    public function __construct($slackBundleService)
    {
        $this->slackBundleClient = $slackBundleService;
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
        $message = $alert->getMessage();
        $client   = $this->slackBundleClient;
        $slackrResponse = $client->send(
            \DZunke\SlackBundle\Slack\Client\Actions::ACTION_POST_MESSAGE,
            [
                'identity' => $this->get('dz.slack.identity_bag')->get('echo_charlie'),
                'channel'  => '#alerts',
                'text'     => $message
            ]
        );
    }
}
