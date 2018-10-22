<?php

namespace App\Form;

use App\Entity\Checkpoint;
use App\Entity\Client;
use App\Entity\Contact;
use App\Entity\Orders;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('internalReference', TextType::class, array(
                'label' => 'Référence externe'
            ))
            ->add('externalReference', TextType::class, array(
                'label' => 'Référence externe',
            ))
            ->add('plannedAt', DateTimeType::class, array(
                'label' => 'Planifié le'
            ))
            ->add('deliveryCheckpoints',EntityType::class, array(
                    'class' => Checkpoint::class,
                    'choice_label' => 'id',
                    'mapped' => false,
                    'attr' => array('class' => 'js-example-basic-single'),
                    'label' => 'Point de passage'
                )
            )
            ->add('contact',EntityType::class, array(
                    'class' => Contact::class,
                    'choice_label' => 'name',
                    'mapped' => false,
                    'attr' => array('class' => 'js-example-basic-single'),
                    'label' => 'Contact'
                )
            )
            ->add('updatedAt', DateTimeType::class, array(
                'label' => 'Mis à jour le'
            ))
            ->add('client', EntityType::class, array(
                    'class' => Client::class,
                    'choice_label' => 'sourceReference',
                    'mapped' => false,
                    'attr' => array('class' => 'js-example-basic-single'),
                    'label' => 'Client',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Orders::class,
        ));
    }
}
