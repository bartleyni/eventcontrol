<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Type;

/**
 * Description of UpdateUserType
 *
 * @author Nick
 */

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UpdateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, array(
                    'required' => false,
                    'attr' => array(
                    'class' => 'form-control'
                )))
                ->add('name', TextType::class, array(
                    'required' => false,
                    'attr' => array(
                    'class' => 'form-control'
                )))
                ->add('email', EmailType::class, array(
                    'required' => false,
                    'attr' => array(
                    'class' => 'form-control'
                )))
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'required' => false,
                    'first_options'  => array('label' => 'Password', 'attr' => array(
                    'class' => 'form-control'
                )),
                    'second_options' => array('label' => 'Repeat Password', 'attr' => array(
                    'class' => 'form-control'
                )),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'validation_groups' => array('update'),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('update'),
            'data_class' => 'AppBundle\Entity\User',
        ));
    }
    
    
    public function getName()
    {
        return 'update_user_form';
    }
}
