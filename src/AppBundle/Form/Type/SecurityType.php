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

class SecurityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('security_log_type', 'entity', array(
                'class' => 'AppBundle:security_log_type',
                'label' => 'Security Log Type',
                'attr' => array(
                    'class' => 'form-control col-sm-10'
                )
            ))
            ->add('security_incident_type', 'entity', array(
                'class' => 'AppBundle:security_incident_type',
                'label' => 'Security Incident Type',
                'attr' => array(
                    'class' => 'form-control col-sm-10'
                )
            ))
            ->add('security_description', 'textarea', array(
                'label' => 'Description',
                'attr' => array(
                    'class' => 'form-control',
                    'rows' => '5'
                )
            ))            
            ->add('security_dispatched', 'checkbox', array(
                'label' => "Security Dispatched?",
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('security_responded', 'checkbox', array(
                'label' => "Security Responded?",
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('security_resolution', 'textarea', array(
                'label' => 'Security Resolution',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'rows' => '5'
                )
            ))              
            ->add('submit_security', 'submit', array(
                'label' => 'Submit Security',
                'attr' => array(
                    'formvalidate' => 'formvalidate',
                    'class' => 'btn btn-success btn-block',
                    'method' => 'POST',
                )
            ))
//            ->add('reset', 'reset', array(
//                'attr' => array(
//                    'class' => 'btn btn-danger btn-block'
//                )
//            ))
            ->setMethod('POST')
        ;
    }
    
    public function getName()
    {
        return 'new_security_form';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\security_log',
        ));
    }
}