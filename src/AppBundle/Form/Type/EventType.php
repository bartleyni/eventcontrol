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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EventType extends AbstractType
{
    public function __construct($em) {
        $this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $qb = $this->em->createQueryBuilder(); 
        
            $qb
                //->select('User.username, User.id')
                ->select('User')
                ->from('AppBundle\Entity\User', 'User')
                ;
            
            $query = $qb->getQuery();
            $operators = $query->getResult();
        
        
        $builder
            ->add('name', 'text', array(
                'label' => 'Event Name',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('client', 'text', array(
                'label' => 'Client',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('event_date', 'datetime', array(
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
            ->add('event_log_start_date', 'datetime', array(
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
            ->add('event_log_stop_date', 'datetime', array(
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
            ->add('event_operators', 'entity', array(
                'label' => 'Event Operator Assignment',
                'mapped' => false,
                'class' => 'AppBundle\Entity\User',
                'multiple' => true,
                'required' => false,
                'data' => $operators,
                'attr' => array(
                    'class' => 'form-control',
                )
            ))
            ->add('submit', 'submit', array(
                'attr' => array(
                    'formvalidate' => 'formvalidate',
                    'class' => 'btn btn-success btn-block',
                    'method' => 'POST',
                )
            ))
            ->add('reset', 'reset', array(
                'attr' => array(
                    'class' => 'btn btn-danger btn-block'
                )
            ))
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
        ));
    }
}