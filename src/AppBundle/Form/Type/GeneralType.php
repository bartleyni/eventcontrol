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

class GeneralType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('general_description', 'textarea', array(
                'label' => 'General Description',
                'required' => true,
                'attr' => array(
                    'class' => 'form-control tsText',
                    'rows' => '5'                    
                )
            ))  
            ->add('general_open', 'checkbox', array(
                'label' => "Incident Open?",
                'required' => false,
                'attr' => array(
                    'class' => 'form-control checkbox'
                )
            ))                
            ->add('submit_general', 'submit', array(
                'label' => 'Submit General',
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
        return 'new_general_form';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\general_log',
        ));
    }
}