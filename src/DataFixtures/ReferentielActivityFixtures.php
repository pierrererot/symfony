<?php

namespace App\DataFixtures;

use App\Entity\ReferentielActivity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class ReferentielActivityFixtures extends AbstractFixtures
{
    const REF_PREFIX = 'ReferentielActivity';
    private const FILENAME_REFERENTIEL = 'referentiel_activity.csv';
    public static $activity_min_id = 1;
    public static $activity_max_id = 0;

    public function load(ObjectManager $manager)
    {
        $i = 1;
        $row = 1;
        if (($handle = fopen( \getcwd() . "\src\DataFixtures\data\\" .  self::FILENAME_REFERENTIEL, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $dataRow = explode(";", $data[0]);
                $dataRow = $this->unquoteArray($dataRow);
                if ($row > 1) {

                    // original columns : ACTI;LIB;FM0_APT;BASE
                    $referentielActivity = new ReferentielActivity();
                    $referentielActivity->setCode($dataRow[0]);
                    $referentielActivity->setLabel($dataRow[1]);
                    $referentielActivity->setFm0Apt($dataRow[2]);
                    $referentielActivity->setDatabaseName($dataRow[3]);
                    $manager->persist($referentielActivity);
                    $this->addReference(static::REF_PREFIX . $i, $referentielActivity);
                    $i++;

                }
                $row++;
            }
            self::$activity_max_id = $i - 1;
            fclose($handle);
        }
        else {
            echo ("Cannot open file " . self::FILENAME_REFERENTIEL);
        }
        $manager->flush();
    }
}
