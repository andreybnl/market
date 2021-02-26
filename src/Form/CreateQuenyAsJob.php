<?php

namespace App\Form;

use App\Entity\Supplier;
use App\Repository\SupplierRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;

class CreateQuenyAsJob extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Query', TextType::class,
                array('attr' => array('class' => 'api_request',
                    'disabled' => false, 'required' => false)))
            ->add('supplier', EntityType::class, [
                'attr' => [
                    'class' => 'api_supplier',
                ],
                'class' => Supplier::class,
                'choice_label' => 'name',
                'query_builder' => function (SupplierRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
            ])
            ->add('Schedule', TextType::class,
                ['attr' => array('class' => 'api_schedule'),
                    'disabled' => false, 'required' => true,
                    // 'help' => "USE ONLY '*' '/' and digits!",
                    //  'constraints' => array(new Length(array('min' => 9)))
                    'constraints' => [new Regex("/^((?:[1-9]?\d|\*)\s*(?:(?:[\/-][1-9]?\d)|(?:,[1-9]?\d)+)?\s*){5}$/")],
                ])
            ->add('Priority', IntegerType::class,
                array('attr' => array('class' => 'api_priority')))
            ->add('Retry', IntegerType::class,
                array('attr' => array('class' => 'api_retry', 'min' => 2, 'max' => 20))
            )
            ->add('TYPE', ChoiceType::class, [
                'attr' => [
                    'class' => 'api_type',
                ],
                'choices' => ['GET' => 'GET', 'POST' => 'POST', 'PATCH' => 'PATCH'],])
            ->add('save', SubmitType::class, array('attr' => [
                'class' => 'submit'],
                'label' => 'Save Query as CronJob'));
    }
}