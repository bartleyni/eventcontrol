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

class LostPropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lost_property_item_lost', 'checkbox', array(
                'label' => "Item Lost?",
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('lost_property_item_found', 'checkbox', array(
                'label' => "Item Found?",
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('lost_property_description', 'textarea', array(
                'label' => 'Item Description',
                'required' => true,
                'attr' => array(
                    'class' => 'form-control',
                    'rows' => '3'
                )
            ))
            ->add('lost_property_contact_details', 'textarea', array(
                'label' => 'Contact Details',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'rows' => '3'
                )
            ))                  
            ->add('lost_property_resolution', 'entity', array(
                'class' => 'AppBundle:lost_property_resolution',
                'label' => 'Lost Property Resolution',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control col-sm-10'
                )
            ))
            ->add('lost_property_resolution_description', 'textarea', array(
                'label' => 'Lost Property Resolution Notes',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control tsText',
                    'rows' => '3'
                )
            ))              
            ->add('submit_lost_property', 'submit', array(
                'label' => 'Submit Lost Property',
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
        return 'new_lostProperty_form';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\lost_property',
        ));
    }
}