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

class MedicalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medical_reported_injury_type', EntityType::class, array(
                'class' => 'AppBundle:medical_reported_injury_type',
                'label' => 'Injury Type',
                'attr' => array(
                    'class' => 'form-control col-sm-10'
                )
            ))
            ->add('medical_description', TextareaType::class, array(
                'label' => 'Description',
                'attr' => array(
                    'class' => 'form-control tsText',
                    'rows' => '5'
                )
            ))            
            ->add('medical_response', EntityType::class, array(
                'class' => 'AppBundle:medical_response',
                'label' => 'Medical Response',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control col-sm-10'
                )
            ))
            ->add('medics_informed', CheckboxType::class, array(
                'label' => "Medics Informed?",
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('nine_nine_nine_required', CheckboxType::class, array(
                'label' => "999 Required?",
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('medical_treatment', EntityType::class, array(
                'class' => 'AppBundle:medical_treatment',
                'label' => 'Medical Treatment',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control col-sm-10'
                )
            ))
            ->add('medical_resolution', EntityType::class, array(
                'class' => 'AppBundle:medical_resolution',
                'label' => 'Medical Resolution',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control col-sm-10'
                )
            ))
            ->add('medical_notes', TextareaType::class, array(
                'label' => 'Medical Notes',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control tsText',
                    'rows' => '5'
                )
            ))              
            ->add('submit_medical', SubmitType::class, array(
                'label' => 'Submit Medical',
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
        return 'new_medical_form';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\medical_log',
        ));
    }
}
