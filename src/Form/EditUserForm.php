<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EditUserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('user', EntityType::class, [
        'attr' => [
            'class' => 'user_list',
        ],
        'class' => User::class,
        'label' => 'Users:',
        'choice_label' => 'email',
        'required' => true
    ])
        ->add('password', TextType::class, [
            'attr' => [
                'class' => 'user_password', 'disabled' => false
            ]])

        ->add('change', SubmitType::class,
            array('attr' => array('class' => 'submit')
            ,'label' => 'Change Password'));
    }

}