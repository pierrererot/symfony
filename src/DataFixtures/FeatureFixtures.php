<?php

namespace App\DataFixtures;

use App\Entity\Feature;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\UsergroupFixtures;

class FeatureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $featureAdmin = new Feature();
        $featureAdmin->setName('feature for admins');
        $featureAdmin->setPath('/path/feature-admin');
        $featureAdmin->setEnabled(true);
        $featureAdmin->addUsergroup($this->getReference(UsergroupFixtures::FEATURE_USERGROUP_ADMIN_REFERENCE));
        $manager->persist($featureAdmin);

        $featureUser = new Feature();
        $featureUser->setName('feature for users');
        $featureUser->setPath('/path/feature-user');
        $featureUser->setEnabled(true);
        $featureUser->addUsergroup($this->getReference(UsergroupFixtures::FEATURE_USERGROUP_USER_REFERENCE));
        $manager->persist($featureUser);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UsergroupFixtures::class,
        );
    }

}
