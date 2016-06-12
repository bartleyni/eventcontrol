<?php
//src/AppBundle/Twig/AppExtension.php

namespace AppBundle\Twig;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;

class AppExtension extends \Twig_Extension
{
    private $doctrine;
  
    public function __construct(RegistryInterface $doctrine) {
        $this->doctrine = $doctrine;
    }
    
    public function getGlobals()
    {
        return array("GlobalTest" => "Hello Test",);
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('activeEventName', array($this, 'getEventName')),
            new \Twig_SimpleFunction('activeEventId', array($this, 'getEventId')),
            new \Twig_SimpleFunction('activeTotalLogs', array($this, 'getTotalLogs')),
            new \Twig_SimpleFunction('activeMedicalLogs', array($this, 'getMedicalLogs')),
            new \Twig_SimpleFunction('activeSecurityLogs', array($this, 'getSecurityLogs')),
            new \Twig_SimpleFunction('activeLostPropertyLogs', array($this, 'getLostPropertyLogs')),
            new \Twig_SimpleFunction('activeOpenLogs', array($this, 'getOpenLogs')),
        );
    }
    
    public function getEventId()
    {
        $em = $this->doctrine->getManager();
        
        $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(array('event_active' => true));
        
        if($event)
        {
            $eventId = $event->getId();
        } else {
            $eventId = 0;
        }
        
        return $eventId;
    }
    
    public function getEventName()
    {
        $em = $this->doctrine->getManager();
        
        $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(array('event_active' => true));
        
        if($event)
        {
            $eventName = $event->getName();
        } else {
            $eventName = "";
        }
        
        return $eventName;
    }
    
    public function getTotalLogs()
    {
        $em = $this->doctrine->getManager();
        
        $qb = $em->createQueryBuilder(); 
        
        $qb
            ->select('count(entry.id)')
            ->from('AppBundle\Entity\log_entries', 'entry')
            ->leftJoin('AppBundle\Entity\event', 'event', 'WITH', 'event.id = entry.event')
            ->where('event.id = :eventId')
            ->setParameter('eventId', $this->getEventId())
            ;

        $totalLogs = $qb->getQuery()->getSingleScalarResult();

        return $totalLogs;
    }
    
    public function getMedicalLogs()
    {
        $em = $this->doctrine->getManager();

        $qb = $em->createQueryBuilder(); 
        
        $qb
            ->select('count(entry.id)')
            ->from('AppBundle\Entity\log_entries', 'entry')
            ->leftJoin('AppBundle\Entity\medical_log', 'med', 'WITH', 'med.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\event', 'event', 'WITH', 'event.id = entry.event')
            ->where($qb->expr()->isNotNull('med.medical_description'))
            ->andWhere('event.id = :eventId')
            ->setParameter('eventId', $this->getEventId())
            ;

        $totalMedical = $qb->getQuery()->getSingleScalarResult();
        //$totalLogs = 25;
        return $totalMedical;
    }
    
    public function getSecurityLogs()
    {
        $em = $this->doctrine->getManager();
        
        $qb = $em->createQueryBuilder(); 
        
        $qb
            ->select('count(entry.id)')
            ->from('AppBundle\Entity\log_entries', 'entry')
            ->leftJoin('AppBundle\Entity\security_log', 'sec', 'WITH', 'sec.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\event', 'event', 'WITH', 'event.id = entry.event')
            ->where($qb->expr()->isNotNull('sec.security_description'))
            ->andWhere('event.id = :eventId')
            ->setParameter('eventId', $this->getEventId())
            ;

        $totalSecurity = $qb->getQuery()->getSingleScalarResult();

        return $totalSecurity;
    }
    
    public function getLostPropertyLogs()
    {
        $em = $this->doctrine->getManager();
        
        $qb = $em->createQueryBuilder(); 
        
        $qb
            ->select('count(entry.id)')
            ->from('AppBundle\Entity\log_entries', 'entry')
            ->leftJoin('AppBundle\Entity\lost_property', 'lost', 'WITH', 'lost.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\event', 'event', 'WITH', 'event.id = entry.event')
            ->where($qb->expr()->isNotNull('lost.lost_property_description'))
            ->andWhere('event.id = :eventId')
            ->setParameter('eventId', $this->getEventId())
            ;

        $totalLostProperty = $qb->getQuery()->getSingleScalarResult();

        return $totalLostProperty;
    }
    
    public function getOpenLogs()
    {
        $em = $this->doctrine->getManager();
        
        $qb = $em->createQueryBuilder(); 
        
        $qb
            ->select('count(entry.id)')
            ->from('AppBundle\Entity\log_entries', 'entry')
            ->leftJoin('AppBundle\Entity\general_log', 'gen', 'WITH', 'gen.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\security_log', 'sec', 'WITH', 'sec.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\medical_log', 'med', 'WITH', 'med.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\lost_property', 'lost', 'WITH', 'lost.log_entry_id = entry.id')
            ->leftJoin('AppBundle\Entity\event', 'event', 'WITH', 'event.id = entry.event')
            ->where($qb->expr()->andX(
                        $qb->expr()->isNotNull('med.medical_description'),
                        $qb->expr()->isNull('med.medical_entry_closed_time')
                    ))
            ->where($qb->expr()->andX(
                            $qb->expr()->isNotNull('sec.security_description'),
                            $qb->expr()->isNull('sec.security_entry_closed_time')
                    ))
            ->where($qb->expr()->andX(
                            $qb->expr()->isNotNull('gen.general_description'),
                            $qb->expr()->isNull('gen.general_entry_closed_time')
                    ))
            ->where($qb->expr()->andX(
                            $qb->expr()->isNotNull('lost.lost_property_description'),
                            $qb->expr()->isNull('lost.lost_property_entry_closed_time')
                    ))
            ->andWhere('event.id = :eventId')
            ->setParameter('eventId', $this->getEventId())
            ;

        $totalOpen = $qb->getQuery()->getSingleScalarResult();

        return $totalOpen;
    }
    
    public function getName()
    {
        return 'AppExtension';
    }
}