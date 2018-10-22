<?php

namespace App\DataFixtures;

use App\Entity\Client;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class ClientFixtures extends Fixture implements DependentFixtureInterface
{
    const USERS_CLIENT_REFERENCE = 'users-client-reference';
    const REF_PREFIX = "client";
    const REF_MAX_NUMBER = 5;

    public function load(ObjectManager $manager)
    {
        // FIRST Client COCA for test Stock
        $client = new Client();
        $client->setSourceReference('COCA');
        $client->setCreatedBy("DATAFIXTURES");

        $numberOrderPerClient = OrdersFixtures::REF_MAX_NUMBER/static::REF_MAX_NUMBER;

        for ( $c = 0 ; $c <  $numberOrderPerClient; $c++) {
            $client->addOrder($this->getReference( OrdersFixtures::REF_PREFIX .( 0 * $numberOrderPerClient + $c)));
        }
        $manager->persist($client);
        $this->addReference(static::REF_PREFIX . 0, $client);

        // Others
        for ($i = 1; $i < static::REF_MAX_NUMBER; $i++) {

            $client = new Client();
            $client->setSourceReference('BOUY' .$i);
            $client->setCreatedBy("DATAFIXTURES");
            $numberOrderPerClient = OrdersFixtures::REF_MAX_NUMBER/static::REF_MAX_NUMBER;

            for ( $c = 0 ; $c <  $numberOrderPerClient; $c++) {
                $client->addOrder($this->getReference( OrdersFixtures::REF_PREFIX .( $i * $numberOrderPerClient + $c)));
            }
            //$client->addAgency($this->getReference(AgencyFixtures::REF_PREFIX . 2));

            $client->addReferentielActivity($this->getReference(ReferentielActivityFixtures::REF_PREFIX . rand(1,2)));
            $client->addReferentielActivity($this->getReference(ReferentielActivityFixtures::REF_PREFIX . rand(3,4)));

            $manager->persist($client);
            $this->addReference(static::REF_PREFIX . $i, $client);
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
            AgencyFixtures::class,
            OrdersFixtures::class,
            ReferentielActivityFixtures::class,
        ];
    }
}
