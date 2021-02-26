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

class ManualRequest extends AbstractType
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
      //            ->add('url', TextType::class
      //                , array('attr' => array('class' => 'api_url'),
      //                   'disabled' => true)
      //            )
               //will come back with dynamic update function //manual_request_url (id)

            ->add('Request', TextType::class
                , array('attr' => array('class' => 'api_request'))
            )
            ->add('supplier', EntityType::class, [  //manual_request_supplier (id)
                'class' => Supplier::class,
                'choice_label' => 'name',
                'query_builder' => function (SupplierRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
            ])
            ->add('TYPE', ChoiceType::class, [
                'choices' => ['GET' => 'GET', 'POST' => 'POST', 'PATCH' => 'PATCH'],
                'attr' => array('class' => 'api_type')])
            ->add('body', TextareaType::class
                , array('attr' => array('class' => 'api_body')
                , 'required' => false))
            ->add('save', SubmitType::class,                  //save/update can be!
                array('attr' => array('class' => 'submit')
                , 'label' => 'Send Request'));

        $builder->get('supplier')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event)
            {
                if(false) {
                    $form = $event->getForm();
                    $form->getParent()
                        ->add('url', TextType::class
                            , array('attr' => array('class' => 'api_url'),
                                'disabled' => true)
                        );

                    $supplier = $this->em->getRepository(Supplier::class)->findOneBy(['name' =>
                        $form->getParent()->get('supplier')->getData()->getName()]);
                    $form->getParent()->get('url')->setData($supplier->getApiUrl());
                    // $form->getParent()->get('Request')->setData($supplier->getApiUrl());
                    $form->getParent()
                        ->add('save', SubmitType::class,
                            array('attr' => array('class' => 'submit')
                            , 'label' => 'Send Request'))
                        ->remove('update');
                }
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();
             //   $supplier = $this->em->getRepository(Supplier::class)->findOneBy(['name' => 'Akeneo']);
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
     //   $resolver->setDefaults(['data_class' => ManualRequest::class, ]);
    }
}