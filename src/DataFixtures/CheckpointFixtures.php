<?php

namespace App\DataFixtures;

use App\Entity\Checkpoint;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class CheckpointFixtures extends Fixture implements DependentFixtureInterface
{
    const REF_PREFIX = "receiver";
    const REF_MAX_NUMBER = 8000;

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < static::REF_MAX_NUMBER; $i++) {
            $receiver = new Checkpoint();

            $receiver
                ->setAddress($this->getReference(AddressFixtures::REF_PREFIX . $i%AddressFixtures::REF_MAX_NUMBER))
                ->setContact($this->getReference(ContactFixtures::REF_PREFIX . $i%ContactFixtures::REF_MAX_NUMBER))
                ->setContactRdv($this->getReference(ContactFixtures::REF_PREFIX . ($i+1)%ContactFixtures::REF_MAX_NUMBER))
                ->setLabel(uniqid("LABEL_".$i."_"))
            ;

            $manager->persist($receiver);

            $this->addReference( static::REF_PREFIX.$i, $receiver);
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
            AddressFixtures::class,
            ContactFixtures::class
        ];
    }
}
