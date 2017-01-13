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

class GeneralType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('general_description', TextareaType::class, array(
                'label' => 'General Description',
                'required' => true,
                'attr' => array(
                    'class' => 'form-control tsText',
                    'rows' => '5'                    
                )
            ))  
            ->add('general_open', CheckboxType::class, array(
                'label' => "Incident Open?",
                'required' => false,
                'attr' => array(
                    'class' => 'form-control checkbox'
                )
            ))                
            ->add('submit_general', SubmitType::class, array(
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
