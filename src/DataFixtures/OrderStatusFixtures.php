<?php

namespace App\DataFixtures;

use App\Entity\OrderStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class OrderStatusFixtures extends Fixture
{
    const CHARGE = 'CH';
    const CHARGE_LITIGE = 'CL';
    const PRISE_EN_CHARGE_RESEAU = 'PC';
    const PRISE_EN_CHARGE_RESEAU_LITIGE = 'PL';
    const EN_TRACTION = 'TR';
    const EN_TRACTION_LITIGE = 'TL';
    const ARRIVE_QUAI_DE_LIVRAISON = 'QL';
    const ARRIVE_QUAI_DE_LIVRAISON_LITIGE = 'QX';
    const EN_LIVRAISON = 'EL';
    const EN_LIVRAISON_LITIGE = 'EX';
    const LIVREE = 'LI';
    const LIVREE_LITIGE = 'LL';
    const FACTURE = 'FC';

    public function load(ObjectManager $manager)
    {
        $status = new OrderStatus();
        $status->setName( static::CHARGE);
        $status->setLabel('Chargé');
        $status->setWeight(1);
        $status->setProgressBarValue(0);
        $status->setIsLitige(false);
        $manager->persist($status);
        $this->addReference( static::CHARGE, $status);

        $status = new OrderStatus();
        $status->setName( static::CHARGE_LITIGE);
        $status->setLabel('Chargé (Litige)');
        $status->setWeight(1);
        $status->setProgressBarValue(0);
        $status->setIsLitige(true);
        $manager->persist($status);
        $this->addReference( static::CHARGE_LITIGE, $status);

        $status = new OrderStatus();
        $status->setName( static::PRISE_EN_CHARGE_RESEAU);
        $status->setLabel('Pris en charge réseau');
        $status->setWeight(2);
        $status->setProgressBarValue(1);
        $status->setIsLitige(false);
        $manager->persist($status);
        $this->addReference( static::PRISE_EN_CHARGE_RESEAU, $status);

        $status = new OrderStatus();
        $status->setName( static::PRISE_EN_CHARGE_RESEAU_LITIGE);
        $status->setLabel('Pris en charge réseau (Litige)');
        $status->setWeight(2);
        $status->setProgressBarValue(1);
        $status->setIsLitige(true);
        $manager->persist($status);
        $this->addReference( static::PRISE_EN_CHARGE_RESEAU_LITIGE, $status);

        $status = new OrderStatus();
        $status->setName( static::EN_TRACTION);
        $status->setLabel('En traction');
        $status->setWeight(3);
        $status->setProgressBarValue(2);
        $status->setIsLitige(false);
        $manager->persist($status);
        $this->addReference( static::EN_TRACTION, $status);

        $status = new OrderStatus();
        $status->setName( static::EN_TRACTION_LITIGE);
        $status->setLabel('En traction (Litige)');
        $status->setWeight(3);
        $status->setProgressBarValue(2);
        $status->setIsLitige(true);
        $manager->persist($status);
        $this->addReference( static::EN_TRACTION_LITIGE, $status);

        $status = new OrderStatus();
        $status->setName( static::ARRIVE_QUAI_DE_LIVRAISON);
        $status->setLabel('Arrivé quai de livraison');
        $status->setWeight(4);
        $status->setProgressBarValue(3);
        $status->setIsLitige(false);
        $manager->persist($status);
        $this->addReference( static::ARRIVE_QUAI_DE_LIVRAISON, $status);

        $status = new OrderStatus();
        $status->setName( static::ARRIVE_QUAI_DE_LIVRAISON_LITIGE);
        $status->setLabel('Arrivé quai de livraison (Litige)');
        $status->setWeight(4);
        $status->setProgressBarValue(3);
        $status->setIsLitige(true);
        $manager->persist($status);
        $this->addReference( static::ARRIVE_QUAI_DE_LIVRAISON_LITIGE, $status);

        $status = new OrderStatus();
        $status->setName( static::EN_LIVRAISON);
        $status->setLabel('En livraison');
        $status->setWeight(5);
        $status->setProgressBarValue(4);
        $status->setIsLitige(false);
        $manager->persist($status);
        $this->addReference( static::EN_LIVRAISON, $status);

        $status = new OrderStatus();
        $status->setName( static::EN_LIVRAISON_LITIGE);
        $status->setLabel('En livraison (Litige)');
        $status->setWeight(5);
        $status->setProgressBarValue(4);
        $status->setIsLitige(true);
        $manager->persist($status);
        $this->addReference( static::EN_LIVRAISON_LITIGE, $status);

        $status = new OrderStatus();
        $status->setName( static::LIVREE);
        $status->setLabel('Livré');
        $status->setWeight(6);
        $status->setProgressBarValue(5);
        $status->setIsLitige(false);
        $manager->persist($status);
        $this->addReference( static::LIVREE, $status);

        $status = new OrderStatus();
        $status->setName( static::LIVREE_LITIGE);
        $status->setLabel('Livré (Litige)');
        $status->setWeight(6);
        $status->setProgressBarValue(5);
        $status->setIsLitige(true);
        $manager->persist($status);
        $this->addReference( static::LIVREE_LITIGE, $status);

        $status = new OrderStatus();
        $status->setName( static::FACTURE);
        $status->setLabel('Facturé');
        $status->setWeight(7);
        $status->setProgressBarValue(6);
        $status->setIsLitige(false);
        $manager->persist($status);
        $this->addReference( static::FACTURE, $status);

        $manager->flush();
    }
}
