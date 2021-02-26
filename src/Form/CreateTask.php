<?php

namespace App\Form;

use App\Entity\Tasks;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;

class CreateTask extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('task', EntityType::class, [
                'attr' => [
                    'class' => 'task',
                ],
                'class' => Tasks::class,
                'choice_label' => 'code',
                'required' => true
            ])
            ->add('Schedule', TextType::class,
                ['attr' => array('class' => 'api_schedule'),
                    'disabled' => false, 'required' => true,
                    'constraints' => [new Regex("/^((?:[1-9]?\d|\*)\s*(?:(?:[\/-][1-9]?\d)|(?:,[1-9]?\d)+)?\s*){5}$/")]
                ])
            ->add('Priority', IntegerType::class,
                array('attr' => array('class' => 'api_priority')))
            ->add('Retry', IntegerType::class,
                array('attr' => array('class' => 'api_retry', 'min' => 2, 'max' => 20))
            )
            ->add('save', SubmitType::class, array('attr' => [
                'class' => 'submit'],
                'label' => 'Save Task as CronJob'))
        ;
    }
}