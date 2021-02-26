<?php

namespace App\Form;

use App\Entity\Supplier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class DeleteSupplier extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('supplier', EntityType::class, [
                'attr' => [
                    'class' => 'sup_choose',
                ],
                'class' => Supplier::class,
                'label' => 'Suppliers:',
                'choice_label' => 'name',
                'required' => true
            ])
            ->add('delete', SubmitType::class,
                [
                    'attr' => [
                        'class' => 'submit',
                        'disabled' => false,
                        'onclick' => 'return confirm("Are you sure?")'
                    ], 'label' => 'Delete Supplier']);
    }
}