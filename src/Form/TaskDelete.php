<?php

namespace App\Form;

use App\Entity\Crontask;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskDelete extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('task_to_delete', EntityType::class, [
                'attr' => [
                    'class' => 'jobs',
                ],
                'class' => Crontask::class,
                'label' => 'Jobs',
                'choice_label' => 'name',
                'required' => true
            ])
            ->add('delete', SubmitType::class, array('attr' => [
                'class' => 'submit'], 'label' => 'Delete CronJob'));
    }
}