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

class MedicalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medical_reported_injury_type', 'entity', array(
                'class' => 'AppBundle:medical_reported_injury_type',
                'label' => 'Injury Type',
                'attr' => array(
                    'class' => 'form-control col-sm-10'
                )
            ))
            ->add('medical_description', 'textarea', array(
                'label' => 'Description',
                'attr' => array(
                    'class' => 'form-control tsText',
                    'rows' => '5'
                )
            ))            
            ->add('medical_response', 'entity', array(
                'class' => 'AppBundle:medical_response',
                'label' => 'Medical Response',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control col-sm-10'
                )
            ))
            ->add('medics_informed', 'checkbox', array(
                'label' => "Medics Informed?",
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('nine_nine_nine_required', 'checkbox', array(
                'label' => "999 Required?",
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('medical_treatment', 'entity', array(
                'class' => 'AppBundle:medical_treatment',
                'label' => 'Medical Treatment',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control col-sm-10'
                )
            ))
            ->add('medical_resolution', 'entity', array(
                'class' => 'AppBundle:medical_resolution',
                'label' => 'Medical Resolution',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control col-sm-10'
                )
            ))
            ->add('medical_notes', 'textarea', array(
                'label' => 'Medical Notes',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control tsText',
                    'rows' => '5'
                )
            ))              
            ->add('submit_medical', 'submit', array(
                'label' => 'Submit Medical',
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
        return 'new_medical_form';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\medical_log',
        ));
    }
}