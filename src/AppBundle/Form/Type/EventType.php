<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EventType extends AbstractType
{
    public function __construct() {
        //$this->em = $this->getDoctrine()->getManager();
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $options['em'];
        $eId = $options['event_id'];
       /* $qb = $em->createQueryBuilder();
            $qb
                ->select('User')
                ->from('AppBundle\Entity\User', 'User')
                ->leftJoin('AppBundle\Entity\user_events', 'UserEvent', 'WITH', 'UserEvent.User_id = User.id')
                ->where('UserEvent.event_id = :eventId')
                ->setParameter('eventId', $eId)
                ;
                        
            $query = $qb->getQuery();
            $operators = $query->getResult();
            */
        
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Event Name',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('client', TextType::class, array(
                'label' => 'Client',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('event_lat_long', TextType::class, array(
                'label' => 'Latitude,Longitude',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('northEastBounds_lat_long', TextType::class, array(
                'label' => 'North East Bound Latitude,Longitude',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('southWestBounds_lat_long', TextType::class, array(
                'label' => 'South West Bound Latitude,Longitude',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('overlay_imageFile', FileType::class, array(
                'label' => 'Overlay Image (.png)',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'
                )
            )) 
            ->add('event_date', DateTimeType::class, array(
                'label' => 'Date of Event',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm',
                'view_timezone' => 'Europe/London',
                'attr' => array(
                    'class' => 'form-control datetimepicker1',
                    'data-provide' => 'datetimepicker1',
                    'data-datetime-format' => 'yyyy-MM-dd HH:mm'
                )
            ))
            ->add('event_log_start_date', DateTimeType::class, array(
                'label' => 'Event Logging Start Date',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'view_timezone' => 'Europe/London',
                'attr' => array(
                    'class' => 'form-control datetimepicker1',
                    'data-provide' => 'datetimepicker2',
                    'data-datetime-format' => 'yyyy-MM-dd HH:mm:ss'
                )
            ))
            ->add('event_log_stop_date', DateTimeType::class, array(
                'label' => 'Event Logging Stop Date',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'view_timezone' => 'Europe/London',
                'attr' => array(
                    'class' => 'form-control datetimepicker1',
                    'data-provide' => 'datetimepicker3',
                    'data-datetime-format' => 'yyyy-MM-dd HH:mm:ss'
                )
            ))            
           /* ->add('event_operators', EntityType::class, array(
                'label' => 'Event Operator Assignment',
                'mapped' => false,
                'class' => 'AppBundle\Entity\User',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'data' => $operators,
                'attr' => array(
                    'class' => 'form-control checkbox',
                )
            ))
            */
            ->add('locations', CollectionType::class, array(
                'label' => 'Locations',
                'entry_type' => LocationType::class,
                'allow_add'  => true,
                'required' => false,
                'by_reference' => false,
                'allow_delete' => true,
                'prototype' => true,
                'delete_empty' => true,
                'error_bubbling' => true,
                'attr' => array(
                    'class' => 'form-control',
                )
            ))    
            ->add('submit', SubmitType::class, array(
                'attr' => array(
                    'formvalidate' => 'formvalidate',
                    'class' => 'btn btn-success btn-block',
                    'method' => 'POST',
                )
            ))
            ->add('reset', ResetType::class, array(
                'attr' => array(
                    'class' => 'btn btn-danger btn-block'
                )
            )) 
            ->add('UPSs', EntityType::class, array(
                'class' => 'AppBundle:UPS',
                'label' => 'UPS',
                'multiple' => true,
                'expanded' => true,
                'attr' => array(
                    'class' => 'form-control checkbox'
                )
            ))
            ->add('Users', EntityType::class, array(
                'class' => 'AppBundle:User',
                'label' => 'Operators',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'attr' => array(
                    'class' => 'form-control checkbox'
                )
            ))
         /*   
            ->add('venues', EntityType::class, array(
                'class' => 'AppBundle:venue',
                'label' => 'Venue',
                'multiple' => true,
                'expanded' => true,
                'attr' => array(
                    'class' => 'form-control checkbox'
                )
            ))
            */
            ->setMethod('POST')
        ;
    }
    
    public function getName()
    {
        return 'new_event_form';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\event',
            'event_id' => null,
            'em' => null,
            'error_mapping' => array(
                'locationText' => 'locations',
                'locationLatLong' => 'locations',
            ),
        ));
    }
    
    public function getBlockPrefix()
    {
        return 'LocationType';
    }
}
