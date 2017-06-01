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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Description of CountAlertType
 *
 * @author Nick
 */
class CountAlertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('desription', TextType::class, array(
                'label' => 'Count Alert Description: ',
                'required' => true,
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('min' => 3)),
                ),
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Description'
                )
            ))
            ->add('upDownBoth', ChoiceType::class, array(
                'label' => 'Count Alert Direction (Up, Down OR Both): ',
                'required' => true,
                'choices'   => array(
                  'UP'   => 'Up',
                  'DOWN'    => 'Down',
                  'BOTH'    => 'Both'
                ),
                'constraints' => array(
                    new NotBlank(),
                ),
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('count', TextType::class, array(
                'label' => 'Count (Integer): ',
                'required' => true,                
                'constraints' => array(
                    new NotBlank(),
                ),
                'attr' => array(
                    'class' => 'form-control'
                )
            ))   
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\VenueCountAlerts',
        ));
    }
}
