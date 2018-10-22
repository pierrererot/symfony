<?php

namespace App\DataFixtures;

use App\Entity\Parameters;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ParametersFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $parameters = new Parameters();
        $parameters->setCommunicationOMP(true);
        $manager->persist($parameters);
        $manager->flush();
    }
}
