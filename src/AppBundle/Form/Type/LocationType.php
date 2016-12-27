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

/**
 * Description of LocationType
 *
 * @author Nick
 */
class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('locationText', 'text', array(
                'label' => 'Location (Text): ',
                'required' => true,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Location'
                )
            ))
            ->add('locationLatLong', 'text', array(
                'label' => 'Co-ordinates (Latitude, Longitude): ',
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Co-ordinates (Latitude, Longitude)',
                    'class' => 'form-control'
                )
            ))    
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Locations',
        ));
    }
}
