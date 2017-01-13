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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SecurityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('security_log_type', EntityType::class, array(
                'class' => 'AppBundle:security_log_type',
                'label' => 'Security Log Type',
                'attr' => array(
                    'class' => 'form-control col-sm-10'
                )
            ))
            ->add('security_incident_type', EntityType::class, array(
                'class' => 'AppBundle:security_incident_type',
                'label' => 'Security Incident Type',
                'attr' => array(
                    'class' => 'form-control col-sm-10'
                )
            ))
            ->add('security_description', TextareaType::class, array(
                'label' => 'Description',
                'attr' => array(
                    'class' => 'form-control tsText',
                    'rows' => '5'
                )
            ))            
            ->add('security_dispatched', CheckboxType::class, array(
                'label' => "Security Dispatched?",
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('security_responded', CheckboxType::class, array(
                'label' => "Security Responded?",
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('security_resolution', TextareaType::class, array(
                'label' => 'Security Resolution',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control tsText',
                    'rows' => '5'
                )
            ))              
            ->add('submit_security', SubmitType::class, array(
                'label' => 'Submit Security',
                'attr' => array(
                    'formvalidate' => 'formvalidate',
                    'class' => 'btn btn-success btn-block',
                    'method' => 'POST',
                )
            ))

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
