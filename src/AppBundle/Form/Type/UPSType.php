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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UPSType extends AbstractType
{
    public function __construct() {
        //$this->em = $this->getDoctrine()->getManager();
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class, array(
                'label' => 'UPS Name',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('location', TextType::class, array(
                'label' => 'UPS Location',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('power', TextType::class, array(
                'label' => 'UPS Power',
                'attr' => array(
                    'class' => 'form-control'
                )
            )) 
                
            ->add('Events', EntityType::class, array(
                'class' => 'AppBundle:event',
                'label' => 'Events',
                'multiple' => true,
                'expanded' => true,
                'disabled' => false,
                'by_reference' => false,
                'attr' => array(
                    'class' => 'form-control checkbox'
                )
            ))
            ->add('submit', SubmitType::class, array(
                'attr' => array(
                    'formvalidate' => 'formvalidate',
                    'class' => 'btn btn-success btn-block',
                    'method' => 'POST',
                )
            ))
            ->add('reset', ResetType::class, array(
                'attr' => array(
                    'class' => 'btn btn-danger btn-block'
                )
            )) 
            ->setMethod('POST')
        ;
    }
    
    public function getName()
    {
        return 'ups_form';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\UPS',
            'ups_id' => null,
            'em' => null,
        ));
    }
}
