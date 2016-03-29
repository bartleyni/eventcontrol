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
                    'class' => 'form-control',
                    'rows' => '5'
                )
            ))            
            ->add('medical_response', 'entity', array(
                'class' => 'AppBundle:medical_response',
                'label' => 'Medical Response',
                'attr' => array(
                    'class' => 'form-control col-sm-10'
                )
            ))
            ->add('nine_nine_nine_required', 'checkbox', array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('medical_entry_closed_time', 'datetime', array(
                'label' => 'Time',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'attr' => array(
                    'class' => 'form-control datetimepicker1',
                    'data-provide' => 'datetimepicker1',
                    'data-datetime-format' => 'yyyy-MM-dd HH:mm:ss'
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
        return 'new_medical_form';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\medical_log',
        ));
    }
}