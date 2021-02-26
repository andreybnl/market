<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RequestResult extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder
        ->add('answer', TextareaType::class
            , array('attr' => array('class' => 'api_answer', 'disabled' => false)))
        ->add('result', TextType::class,
            array('attr' => array('class' => 'api_result', 'disabled' => true)));
    }
}