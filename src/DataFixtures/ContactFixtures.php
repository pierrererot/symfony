<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class ContactFixtures extends Fixture
{
    const REF_PREFIX = 'contact';
    const REF_MAX_NUMBER = 4000;

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < static::REF_MAX_NUMBER; $i++) {

            $contact = new Contact();
            $j = $i;
            if (strlen($i) == 1) {
                $j = '0' . $i;
            }
            $contact
                ->setName( 'M contact numéro:'.$i)
                ->setPhoneNumber('01 02 03 04 ' . $j)
            ;
            $manager->persist($contact);

            $this->addReference( static::REF_PREFIX.$i, $contact);
        }

        $manager->flush();

    }
}
