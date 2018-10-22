<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class AbstractFixtures extends Fixture
{
    public function load(ObjectManager $manager) {
    }

    public function unquoteArray($arrayWithQuotes) {
        $arrayWithoutQuotes = [];
        foreach ($arrayWithQuotes as $aRow) {
            $arrayWithoutQuotes[] = str_replace("\"", "", $aRow);
        }
        return $arrayWithoutQuotes;
    }

    public function getDependencies()
    {
    }
}
