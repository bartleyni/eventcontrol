<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Alert;
use AppBundle\Entity\User;
use AppBundle\Entity\event;
use AppBundle\Entity\log_entries;
use AppBundle\Entity\general_log;
use AppBundle\Entity\medical_log;
use AppBundle\Entity\security_log;
use AppBundle\Entity\lost_property;
use Doctrine\ORM\Event\LifecycleEventArgs;
use RedjanYm\FCMBundle;
use DZunke\SlackBundle;
use \DZunke\SlackBundle\Slack\Entity\MessageAttachment;
use Doctrine\ORM\EntityManager;

class LogListener
{
    private $slackBundle_client;
    private $slackBundle_identity_bag;
    protected $container;

    public function __construct($client, $identity_bag,\Symfony\Component\DependencyInjection\Container $container)
    {
        $this->slackBundle_client = $client;
        $this->slackBundle_identity_bag = $identity_bag;
        //$this->slackBundle_connection = $connection;
        $this->container = $container;
    }
    
    public function postPersist(LifecycleEventArgs $args)
   {        
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
                
        if ($entity instanceof medical_log || $entity instanceof security_log) {
            
            $log = $entity->getLogEntryId();
            $users = $log->getEvent()->getUsers();
            $this->postToSlack($log);
            if ($users) {
                $this->sendFirebaseLogMessage($log, $entity, $users);
            }
        }
    }
    
    public function postUpdate(LifecycleEventArgs $args)
    {        
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        
        if ($entity instanceof log_entries) {
            $users = $entity->getEvent()->getUsers();
            if ($users) {
                $this->sendFirebaseMessage($entity, $users);
            }
        }
    }
    
    private function postToSlack(log_entries $log)
    {
        $breaks = array("<br />","<br>","<br/>");
        $unformatted_title = "Log Entry";
        $title = str_ireplace($breaks, "\r\n", $unformatted_title);
        $unformatted_message = $log->getLogBlurb();
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
    
    private function sendFirebaseMessage(log_entries $log, $users)
    {
        foreach($users as $user){
            $token = $user->getFirebaseID();
            if($token){
                //$fcmClient = $this->getContainer()->get('redjan_ym_fcm.client');
                $fcmClient = $this->container->get('redjan_ym_fcm.client');
                $notification = $fcmClient->createDeviceNotification(
                    "Log entry", 
                    $log->getLogBlurb(),
                    $token
                );
                $notification->setData(["type" => "Log",]);
                $fcmClient->sendNotification($notification);
            }
        }
    }
    
    private function sendFirebaseLogMessage(log_entries $log, $entity, $users)
    {
        $title = "";
        $description = "";
        
        if ($entity instanceof medical_log) {
            $title = "New Log:".$entity->getMedicalReportedInjuryType();
            $description = $entity->getMedicalDescription();
        }
        
        
        if ($entity instanceof security_log) {
            $title = "New Log:".$entity->getSecurityIncidentType();
            $description = $entity->getSecurityDescription();
        }
        
        foreach($users as $user){
            $token = $user->getFirebaseID();
            if($token){
                //$fcmClient = $this->getContainer()->get('redjan_ym_fcm.client');
                $fcmClient = $this->container->get('redjan_ym_fcm.client');
                $notification = $fcmClient->createDeviceNotification(
                    "title", 
                    $log->getLogBlurb()."<br>".$description,
                    $token
                );
                $notification->setData(["type" => "Log",]);
                $fcmClient->sendNotification($notification);
            }
        }
    }
    
    
}
