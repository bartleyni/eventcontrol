<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntergerType;


class VenueCameraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('camera_id', 'entity', array(
                'class' => 'AppBundle:camera',
                'label' => 'Camera',
                'multiple' => true,
                'expanded' => true,
                'attr' => array(
                    'class' => 'form-control checkbox'
                )
            ))
            ->add('inverse', 'checkbox', array(
                'label' => "Inverse camera",
                'required' => false,
                'attr' => array(
                    'class' => 'form-control checkbox'
                )
            ))
            ->setMethod('POST');
    }
    public function getName()
    {
        return 'venue_camera_form';
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'AppBundle\Entity\venue_camera'));
    }
}