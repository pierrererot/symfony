<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Agency;
use App\Entity\Checkpoint;
use App\Entity\Client;
use App\Entity\Contact;
use App\Entity\Orders;
use App\Entity\ReferentielActivity;
use App\Entity\ReferentielBenefit;
use App\Entity\ReferentielEAN;
use App\Entity\ReferentielExploitation;
use App\Entity\Users;
use App\Repository\AddressRepository;
use App\Repository\ClientRepository;
use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Cache\Adapter\DoctrineAdapter;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\RegistryInterface as Doctrine;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OrdersManualType extends AbstractType
{
    private $doctrine;
    private $securityContext;

    public function __construct(Doctrine $doctrine, TokenStorageInterface $securityContext){
        $this->doctrine = $doctrine;
        $this->securityContext = $securityContext;
    }

    private function fillClients() {

        /** @var UserRepository $userRepository */
        $userRepository = $this->doctrine->getRepository(Users::class);
        $username = $this->securityContext->getToken()->getUser()->getUsername();
        $userId = $userRepository->findByLogin($username)->getId();
        /** @var ClientRepository $clientRepository */
        $clientRepository = $this->doctrine->getRepository(Client::class);

        $data = $clientRepository->findByUser($userId);
        return $data;
    }

    private function fillContact() {

        /** @var UserRepository $userRepository */
        $userRepository = $this->doctrine->getRepository(Users::class);
        $username = $this->securityContext->getToken()->getUser()->getUsername();
        $userId = $userRepository->findByLogin($username)->getId();
        /** @var ContactRepository $contactRepository */
        $contactRepository = $this->doctrine->getRepository(Contact::class);

        $data = $contactRepository->findByUser($userId);
        return $data;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('client',EntityType::class, array(
                    'class' => Client::class,
                    'choice_label' => 'sourceReference',
                    'choices' => $this->fillClients(),
                    'attr' => array('class' => 'js-example-basic-single select_order'),
                    'label' => 'Clients'
                )
            )
            ->add('contact',EntityType::class, array(
                    'class' => Contact::class,
                    'choices' => $this->fillContact(),
                    'choice_label' => 'name',
                    'attr' => array('class' => 'js-example-basic-single select_order'),
                    'label' => 'Contact',
                    'mapped' => false,
                )
            )
            ->add('referentielActivity',EntityType::class, array(
                    'class' => ReferentielActivity::class,
                    'choice_label' => 'label',
                    'mapped' => false,
                    'attr' => array('class' => 'js-example-basic-single select_order'),
                    'label' => 'Activité'
                )
            )
            ->add('referentielBenefit',EntityType::class, array(
                    'class' => ReferentielBenefit::class,
                    'choice_label' => 'label',
                    'mapped' => false,
                    'attr' => array('class' => 'js-example-basic-single select_order'),
                    'label' => 'Prestation'
                )
            )
            ->add('referentielExploitation',EntityType::class, array(
                    'class' => ReferentielExploitation::class,
                    'choice_label' => 'label',
                    'mapped' => false,
                    'attr' => array('class' => 'js-example-basic-single select_order'),
                    'label' => 'Portefeuille'
                )
            )
            ->add('comment', TextareaType::class, array(
                    'attr' => array('class' => 'tinymce'),
                    'mapped' => false,
                    'required' => false,
                    'label' => 'Commentaire'
                )
            )
            ->add('referentielEAN1',EntityType::class, array(
                    'class' => ReferentielEAN::class,
                    'choice_label' => 'label',
                    'mapped' => false,
                    'attr' => array('class' => 'js-example-basic-single select_order'),
                    'label' => 'Produit',
                    'required' => true,
                )
            )
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Orders::class,
        ));
    }
}
