<?php

namespace App\Form;

use App\Entity\Supplier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ChooseSupplier extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {$builder
        ->add('supplier', EntityType::class, [
        'attr' => [
            'class' => 'sup_choose',
        ],
        'class' => Supplier::class,
        'choice_label' => 'name'
    ])
        ->add('save', SubmitType::class,
            array('attr' => array('class' => 'submit')
            ,'label' => 'Select supplier to edit'));
    }
}