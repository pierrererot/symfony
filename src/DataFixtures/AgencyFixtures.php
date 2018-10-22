<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Agency;
use App\Entity\Checkpoint;
use App\Entity\Contact;
use App\Entity\Trader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Cache\Adapter\TraceableAdapter;


class AgencyFixtures extends AbstractFixtures implements DependentFixtureInterface
{
    const REF_PREFIX = "agency";

    private const FILENAME_AGENCY = 'agency1.csv';

    public function load(ObjectManager $manager)
    {
        $row = 1;
        if (($handle = fopen( \getcwd() . "\src\DataFixtures\data\\" .  self::FILENAME_AGENCY, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $dataRow = explode(';', $data[0]);
                $dataRow = $this->unquoteArray($dataRow);
                if ($row > 1) {
                    // original columns : AGCE;ENS1;ENS2;ENS3;ADR1;ADR2;ADR3;CP;VILLE;TELPH;TELEC;EMAIL;BASENAME

                    $address = new Address();
                    $address->setRecipient1($dataRow[1]);
                    $address->setRecipient2($dataRow[2]);
                    $address->setRecipient3($dataRow[3]);
                    $address->setStreet1($dataRow[4]);
                    $address->setStreet2($dataRow[5]);
                    $address->setStreet3($dataRow[6]);
                    $address->setPostcode($dataRow[7]);
                    $address->setCity($dataRow[8]);

                    $contact = new Contact();
                    $contact->setPhoneNumber($dataRow[9]);
                    $contact->setFaxNumber($dataRow[10]);
                    $contact->setEmail($dataRow[11]);

                    $checkpoint = new Checkpoint();
                    $checkpoint->setContact($contact);
                    $checkpoint->setAddress($address);

                    $agency = new Agency();
                    $agency->setCheckpoint($checkpoint);
                    $agency->setDatabase($dataRow[12]);
                    $agency->setCode(trim($dataRow[0]));
                    $agency->addTrader($this->getReference(TraderFixtures::AGENCY_TRADER_REFERENCE . rand(TraderFixtures::$trader_min_id, TraderFixtures::$trader_max_id)));
                    $manager->persist($agency);
                    $this->addReference(static::REF_PREFIX. $row, $agency);
                }
                $row++;
            }
            fclose($handle);
        }
        else {
            echo ("Cannot open file " . self::FILENAME_AGENCY);
        }
        //$manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TraderFixtures::class,
            CheckpointFixtures::class
        );
    }
}
