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

class LogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('operator', 'entity', array(
                'class' => 'AppBundle:User',
                'label' => 'Operator ID',
                'attr' => array(
                    'class' => 'form-control col-sm-10'
                )
            ))
            ->add('log_entry_open_time', 'datetime', array(
                'label' => 'Time',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'attr' => array(
                    'class' => 'form-control datetimepicker1',
                    'data-provide' => 'datetimepicker1',
                    'data-datetime-format' => 'yyyy-MM-dd HH:mm:ss'
                )
            ))
            ->add('log_blurb', 'textarea', array(
                'label' => 'Short Description',
                'attr' => array(
                    'class' => 'form-control',
                    'rows' => '2'
                )
            ))
            ->add('location', 'text', array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('reported_by', 'text', array(
                'attr' => array(
                    'class' => 'form-control'
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
        return 'new_entry_form';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\log_entries',
        ));
    }
}