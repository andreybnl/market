<?php

namespace App\Form;

use App\Entity\Supplier;
use App\Entity\Tasks;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CronTaskRun extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('task', EntityType::class,
                [
                    'attr' => [
                        'class' => 'tasks',
                    ],
                    'class' => Tasks::class,
                    'label' => 'Tasks',
                    'choice_label' => 'code',
                    'required' => true
                ])
            ->add('run', SubmitType::class, array('attr' => [
                'class' => 'submit'], 'label' => 'Run Task once'));
    }
}