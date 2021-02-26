<?php

namespace App\Form;

use App\Entity\MarketQuery;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityManagerInterface;

class MarketQueryRequest extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', EntityType::class, [
                'attr' => [
                    'class' => 'query_choose',
                ],
                'class' => MarketQuery::class,
                'choice_label' => 'label'
            ])
            ->add('run', SubmitType::class,
                array('attr' => array('class' => 'submit')
                , 'label' => 'Run Query'))
            ->add('delete', SubmitType::class,
                array('attr' => array('class' => 'submit'
                , 'onclick' => 'return confirm("Are you sure?")')
                , 'label' => 'Delete Query'));
    }
}