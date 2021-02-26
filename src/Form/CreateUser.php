<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateUser extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email', TextType::class,
        [
            'attr' => [
                'class' => 'user_email', 'disabled' => false
            ]])
        ->add('password', TextType::class,
            [
                'attr' => [
                    'class' => 'user_password', 'disabled' => false
                ]])
        ->add('create', SubmitType::class,
            array('attr' => array('class' => 'submit')
            ,'label' => 'Create User'));
    }
}