<?php

namespace App\Form;

use App\Entity\Supplier;
use App\Repository\SupplierRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MarketManualRequest extends AbstractType
{
    private $em;

    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('supplier', EntityType::class, [
                'attr' => [
                    'class' => 'sup_choose',
                ],
                'class' => Supplier::class,
                'choice_label' => 'name'
            ])
            ->add('Request', TextType::class
                , array('attr' => array('class' => 'api_request'))
            )
            ->add('TYPE', ChoiceType::class, [
                'choices' => ['GET' => 'GET', 'POST' => 'POST', 'PATCH' => 'PATCH'],
                'attr' => array('class' => 'api_type')])
            ->add('send', SubmitType::class,
                array('attr' => array('class' => 'submit')
                , 'label' => 'Send Request'))
            ->add('label', TextType::class
                , array('attr' => array('class' => 'api_label'))
            )
            ->add('save', SubmitType::class,
                array('attr' => array('class' => 'submit')
                , 'label' => 'Save as Query'));

    }
}