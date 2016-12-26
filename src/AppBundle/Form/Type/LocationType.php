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
                'attr' => array(
                    'class' => 'collection'
                )
            ))
            ->add('locationLatLong', 'text', array(
                'label' => 'Co-ordinates (Latitude, Longitude): ',
                'attr' => array(
                    'class' => 'collection'
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
