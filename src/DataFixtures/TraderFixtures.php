<?php

namespace App\DataFixtures;

use App\Entity\Trader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class TraderFixtures extends AbstractFixtures
{
    private const FILENAME_TRADER = 'trader.csv';
    public const AGENCY_TRADER_REFERENCE = 'agency-trader';
    public static $trader_min_id = 0;
    public static $trader_max_id = 0;

    public function load(ObjectManager $manager)
    {
        $row = 1;
        if (($handle = fopen( \getcwd() . "\src\DataFixtures\data\\" .  self::FILENAME_TRADER, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $dataRow = explode(';', $data[0]);
                $dataRow = $this->unquoteArray($dataRow);
                if ($row > 1) {
                    // original columns : id, libelle, base_name, id_data, code, joncture.
                    $trader = new Trader();
                    $trader->setLabel($dataRow[1]);
                    $trader->setDatabaseName($dataRow[2]);
                    $trader->setCode($dataRow[3]);
                    $trader->setJoncture($dataRow[4]);
                    $trader->setPhoneNumber('XXXXXXXXXX');
                    $trader->setFaxNumber('XXXXXXXXXX');

                    // Email field treatment : allow alphanumeric characters only and transform them into lower case --- START
                    $tempEmail = preg_replace("/[^A-Za-z0-9 ]/", '', $dataRow[4]);
                    $tempEmail = strtolower($tempEmail) . '@gmail.com';
                    // Email field treatment : allow alphanumeric characters only and transform them into lower case --- END

                    $trader->setEmail($tempEmail);
                    if (self::$trader_min_id == 0 || self::$trader_min_id > $row) {
                        self::$trader_min_id = $row;
                    }
                    if (self::$trader_max_id == 0 || self::$trader_max_id < $row) {
                        self::$trader_max_id = $row;
                    }

                    $this->addReference(self::AGENCY_TRADER_REFERENCE . $row, $trader);
                    $manager->persist($trader);
                }
                $row++;
            }
            fclose($handle);
        }
        else {
            echo ("Cannot open file " . self::FILENAME_TRADER);
        }
        $manager->flush();
    }
}
