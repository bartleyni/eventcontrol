<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AuditLog
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Xiidea\EasyAuditBundle\Entity\BaseAuditLog;

/**
 * @ORM\Entity
 * @ORM\Table(name="audit_log")
 */

class AuditLog extends BaseAuditLog
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Type Of Event(Internal Type ID)
     *
     * @var string
     * @ORM\Column(name="type_id", type="string", length=200, nullable=false)
     */
    protected $typeId;

    /**
     * Type Of Event
     *
     * @var string
     * @ORM\Column(name="type", type="string", length=200, nullable=true)
     */
    protected $type;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     * Time Of Event
     * @var \DateTime
     * @ORM\Column(name="event_time", type="datetime")
     */
    protected $eventTime;

    /**
     * @var string
     * @ORM\Column(name="user", type="string", length=255)
     */
    protected $user;

    /**
     * @var string
     * @ORM\Column(name="impersonatingUser", type="string", length=255)
     */
    protected $impersonatingUser;

    /**
     * @var string
     * @ORM\Column(name="ip", type="string", length=20, nullable=true)
     */
    protected $ip;    

}
