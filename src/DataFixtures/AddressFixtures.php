<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class AddressFixtures extends AbstractFixtures
{
    const REF_PREFIX = "add";
    const REF_MAX_NUMBER = 100;

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < static::REF_MAX_NUMBER; $i++) {

            $address = new Address();
            $address
                ->setStreet1( $i .' de ma super rue')
                ->setPostcode("7500".$i)
                ->setCity("Ville ".$i)
                ->setCountry('FR')
            ;
            $manager->persist($address);

            $this->addReference(static::REF_PREFIX.$i, $address);
        }

        $manager->flush();

    }

}
