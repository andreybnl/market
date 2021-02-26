<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class DeleteUser extends AbstractType
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
            ->add('delete', SubmitType::class,
                [
                    'attr' => [
                        'disabled' => false,
                        'class' => 'submit',
                        'onclick' => 'return confirm("Are you sure?")'
                    ], 'label' => $options['label']]);
    }
}