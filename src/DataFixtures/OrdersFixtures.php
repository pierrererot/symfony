<?php

namespace App\DataFixtures;

use App\Entity\GeographyInformation;
use App\Entity\OrderPhase;
use App\Entity\Orders;
use App\Entity\ReferentielBenefit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class OrdersFixtures extends Fixture implements DependentFixtureInterface
{
    const REF_PREFIX = 'Order';
    const REF_MAX_NUMBER = 20;

    public const COMMENT_ORDER_REFERENCE = 'comment-order';

    public function load(ObjectManager $manager)
    {
        $statusList = [
            $this->getReference(OrderStatusFixtures::PRISE_EN_CHARGE_RESEAU),
            $this->getReference(OrderStatusFixtures::PRISE_EN_CHARGE_RESEAU_LITIGE),
            $this->getReference(OrderStatusFixtures::FACTURE),
            $this->getReference(OrderStatusFixtures::LIVREE_LITIGE),
            $this->getReference(OrderStatusFixtures::LIVREE),
            $this->getReference(OrderStatusFixtures::EN_LIVRAISON_LITIGE),
            $this->getReference(OrderStatusFixtures::EN_LIVRAISON),
            $this->getReference(OrderStatusFixtures::ARRIVE_QUAI_DE_LIVRAISON_LITIGE),
            $this->getReference(OrderStatusFixtures::ARRIVE_QUAI_DE_LIVRAISON),
            $this->getReference(OrderStatusFixtures::CHARGE_LITIGE),
            $this->getReference(OrderStatusFixtures::CHARGE),
            $this->getReference(OrderStatusFixtures::EN_TRACTION),
            $this->getReference(OrderStatusFixtures::EN_TRACTION_LITIGE),
        ];

        $steps = [];
        for ($i = 0; $i < static::REF_MAX_NUMBER; $i++) {

            $order = new Orders();

            $plannedAt = new \DateTime();
            $plannedAt->modify('+ '.rand(1,25).' day');

            $updatedAt = new \DateTime();
            $updatedAt = $updatedAt->format('Y-m-d H:i:s');

            $order->setFinalCheckpoint(clone($this->getReference(CheckpointFixtures::REF_PREFIX.( (rand(0, CheckpointFixtures::REF_MAX_NUMBER/2))))));
            $order->setInitialCheckpoint(clone($this->getReference(CheckpointFixtures::REF_PREFIX.( rand(CheckpointFixtures::REF_MAX_NUMBER/2, CheckpointFixtures::REF_MAX_NUMBER)))));
            $order
                ->setBenefit($this->getReference(ReferentielBenefitFixtures::REF_PREFIX.(rand(1,10))))
                ->setDeal("aff".$i%3)
                ->setStatus($statusList[array_rand($statusList)])
                ->setCreatedBy("DATAFIXTURE")
                ->setSourceReference(uniqid("TEST_REF_"))
                ->setUpdatedBy($updatedAt)

            ;

            $numberStepPerOrder = OrderPhaseFixtures::REF_MAX_NUMBER / static::REF_MAX_NUMBER;
             for ($c = 0; $c < $numberStepPerOrder ; $c++ ) {
                 $order->addPhase($this->getReference(OrderPhaseFixtures::REF_PREFIX.( ($i*$numberStepPerOrder + $c))));
             }



            $this->addReference(static::REF_PREFIX.$i, $order);
            $manager->persist($order);
            if ($i < 10) {
                $this->addReference(self::COMMENT_ORDER_REFERENCE . $i, $order);
            }
        }
        $manager->flush();

    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    function getDependencies()
    {
        return [
            CheckpointFixtures::class,
            OrderPhaseFixtures::class,
            ContactFixtures::class,
            OrderStatusFixtures::class,
            ReferentielBenefitFixtures::class
        ];
    }
}
