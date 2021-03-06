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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array('attr' => array(
                    'class' => 'form-control'
                )))
            ->add('username', TextType::class, array('attr' => array(
                    'class' => 'form-control'
                )))
            ->add('name', TextType::class, array('attr' => array(
                    'class' => 'form-control'
                )))
            ->add('apiToken', TextType::class, array('attr' => array(
                    'class' => 'form-control',
                    'disabled' => true
                )))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password', 'attr' => array(
                    'class' => 'form-control'
                )),
                'second_options' => array('label' => 'Repeat Password', 'attr' => array(
                    'class' => 'form-control'
                )),
                
            )
        );
    }
    public function getName()
    {
        return 'new_user_form';
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'validation_groups' => array('registration'),
        ));
    }
}
