<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Repository\SupplierRepository;
use App\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;

class EditSupplier extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class, [
                'attr' => [
                    'class' => 'sup_label',
                ]])
            ->add('api_url', TextType::class, [
                'attr' => [
                    'class' => 'sup_url',
                ]])
            ->add('token_url', TextType::class, [
                'attr' => [
                    'class' => 'sup_token'
                ], 'required' => false])
            ->add('oauth2_key', TextType::class, [
                'attr' => [
                    'class' => 'sup_oath',
                ], 'required' => false])
            ->add('login', TextType::class, [
                'attr' => [
                    'class' => 'sup_login',
                ]])
            ->add('password', TextType::class, [
                'attr' => [
                    'class' => 'sup_password',
                ]])
            ->add('grant_type', TextType::class, [
                'attr' => [
                    'class' => 'sup_grant',
                ], 'required' => false])
            ->add('delay', IntegerType::class, [
                'attr' => [
                    'class' => 'sup_delay', 'min' => 3, 'max' => 100
                ]])
            ->add('new', CheckboxType::class, [
                'attr' => [
                    'class' => 'sup_new',
                ],
                'label' => 'Create new supplier with this data?',
                'required' => false,
            ])
            ->add('name', HiddenType::class)
            ->add('save', SubmitType::class,
                array('attr' => array('class' => 'submit')
                , 'label' => 'Edit supplier data'));
    }
}