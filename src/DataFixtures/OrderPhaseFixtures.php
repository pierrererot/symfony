<?php

namespace App\DataFixtures;

use App\Entity\OrderPhase;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class OrderPhaseFixtures extends Fixture
{
    const REF_PREFIX = "phase";
    const REF_MAX_NUMBER = 40;

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < static::REF_MAX_NUMBER; $i++) {

            $phase = new OrderPhase();
            $phase->setSourceReference('OTTP '.$i);
            $phase->setCreatedBy(self::class);

            $manager->persist($phase);

            $this->addReference(static::REF_PREFIX.$i, $phase);
        }
        $manager->flush();
    }
}
