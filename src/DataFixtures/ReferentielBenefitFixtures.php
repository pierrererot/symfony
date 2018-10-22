<?php

namespace App\DataFixtures;

use App\Entity\ReferentielBenefit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class ReferentielBenefitFixtures extends AbstractFixtures
{
    const REF_PREFIX = 'ReferentielBenefit';
    private const FILENAME_REFERENTIEL = 'referentiel_benefit.csv';
    public static $benefit_min_id = 1;
    public static $benefit_max_id = 0;

    public function load(ObjectManager $manager)
    {
        $i = 1;
        $row = 1;
        if (($handle = fopen( \getcwd() . "\src\DataFixtures\data\\" .  self::FILENAME_REFERENTIEL, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $dataRow = explode(";", $data[0]);
                $dataRow = $this->unquoteArray($dataRow);
                if ($row > 1) {

                    // original columns : PRST;LIB;FM0_APT;FM1_APT;TXAUG
                    $referentielBenefit = new ReferentielBenefit();
                    $referentielBenefit->setCode($dataRow[0]);
                    $referentielBenefit->setLabel($dataRow[1]);
                    $referentielBenefit->setFm0Apt($dataRow[2]);
                    $referentielBenefit->setFm1Apt($dataRow[3]);
                    $referentielBenefit->setDatabaseName($dataRow[3]);
                    $this->addReference(static::REF_PREFIX . $i, $referentielBenefit);
                    $i++;
                    $manager->persist($referentielBenefit);
                }
                $row++;
            }
            self::$benefit_max_id = $i - 1;
            fclose($handle);
        }
        else {
            echo ("Cannot open file " . self::FILENAME_REFERENTIEL);
        }
        $manager->flush();
    }
}
