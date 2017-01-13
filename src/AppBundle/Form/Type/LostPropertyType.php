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

class LostPropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lost_property_item_lost', CheckboxType::class, array(
                'label' => "Item Lost?",
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('lost_property_item_found', CheckboxType::class, array(
                'label' => "Item Found?",
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('lost_property_description', TextareaType::class, array(
                'label' => 'Item Description',
                'required' => true,
                'attr' => array(
                    'class' => 'form-control',
                    'rows' => '3'
                )
            ))
            ->add('lost_property_contact_details', TextareaType::class, array(
                'label' => 'Contact Details',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'rows' => '3'
                )
            ))                  
            ->add('lost_property_resolution', EntityType::class, array(
                'class' => 'AppBundle:lost_property_resolution',
                'label' => 'Lost Property Resolution',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control col-sm-10'
                )
            ))
            ->add('lost_property_resolution_description', TextareaType::class, array(
                'label' => 'Lost Property Resolution Notes',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control tsText',
                    'rows' => '3'
                )
            ))              
            ->add('submit_lost_property', SubmitType::class, array(
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
