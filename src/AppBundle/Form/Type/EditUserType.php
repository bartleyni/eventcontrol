<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Type;

/**
 * Description of EditUserType
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

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text', array(
                    'required' => false,
                    'attr' => array(
                    'class' => 'form-control'
                )))
                ->add('name', 'text', array(
                    'required' => false,
                    'attr' => array(
                    'class' => 'form-control'
                )))
                ->add('email', 'email', array(
                    'required' => false,
                    'attr' => array(
                    'class' => 'form-control'
                )))
                //->add('oldPassword', 'password')
                ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'The password fields must match.',
                    'required' => false,
                    'first_options'  => array('label' => 'Password', 'attr' => array(
                    'class' => 'form-control'
                )),
                    'second_options' => array('label' => 'Repeat Password', 'attr' => array(
                    'class' => 'form-control'
                ))))
                ->add('groups', 'entity', array(
                'class' => 'AppBundle:Group',
                'label' => 'Groups',
                'multiple' => true,
                'attr' => array(
                    'class' => 'form-control selectpicker'
                )
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'validation_groups' => array('update'),
        ));
    }

    public function getName()
    {
        return 'edit_user_form';
    }
}