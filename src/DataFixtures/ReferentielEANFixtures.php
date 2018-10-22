<?php

namespace App\DataFixtures;

use App\Entity\ReferentielEAN;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class ReferentielEANFixtures extends AbstractFixtures
{
    private const FILENAME_REFERENTIEL = 'referentiel_ean.csv';
    const REF_PREFIX = 'ReferentielEAN';
    public static $ean_min_id = 1;
    public static $ean_max_id = 0;

    public function load(ObjectManager $manager)
    {
        $i = 1;
        $row = 1;
        if (($handle = fopen( \getcwd() . "\src\DataFixtures\data\\" .  self::FILENAME_REFERENTIEL, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $dataRow = explode(";", $data[0]);
                $dataRow = $this->unquoteArray($dataRow);
                if ($row > 1) {

                    // original columns : EAN;DESIGN;FM0_SPT;FM0_SPT_1;POIDSM;LONGM;LARGM;HAUTM
                    $referentielEAN = new ReferentielEAN();
                    $referentielEAN->setCode($dataRow[0]);
                    $referentielEAN->setLabel($dataRow[1]);
                    $referentielEAN->setFm0Apt($dataRow[2]);
                    $referentielEAN->setFm0Spt($dataRow[3]);
                    $referentielEAN->setWeight($dataRow[4]);
                    $referentielEAN->setLength($dataRow[5]);
                    $referentielEAN->setWidth($dataRow[6]);
                    $referentielEAN->setHeight($dataRow[7]);
                    $referentielEAN->setDatabaseName('TXTTR'); // TODO with related databases
                    $this->addReference(static::REF_PREFIX . $i, $referentielEAN);
                    $i++;
                    $manager->persist($referentielEAN);
                }
                $row++;
            }
            self::$ean_max_id = $i - 1;
            fclose($handle);
        }
        else {
            echo ("Cannot open file " . self::FILENAME_REFERENTIEL);
        }
        $manager->flush();
    }
}
